<?php

declare(strict_types=1);

/**
 * Build a GraphQL query for a contribution graph
 *
 * @param string $user GitHub username to get graphs for
 * @param int $year Year to get graph for
 * @return string GraphQL query
 */
function buildContributionGraphQuery(string $user, int $year): string
{
    $start = "$year-01-01T00:00:00Z";
    $end = "$year-12-31T23:59:59Z";
    return "query {
        user(login: \"$user\") {
            createdAt
            contributionsCollection(from: \"$start\", to: \"$end\") {
                contributionYears
                contributionCalendar {
                    weeks {
                        contributionDays {
                            contributionCount
                            date
                        }
                    }
                }
            }
        }
    }";
}

/**
 * Execute multiple requests with cURL and handle GitHub API rate limits and errors
 *
 * @param string $user GitHub username to get graphs for
 * @param array<int> $years Years to get graphs for
 * @return array<int,stdClass> List of GraphQL response objects with years as keys
 */
function executeContributionGraphRequests(string $user, array $years): array
{
    $tokens = [];
    $requests = [];
    // build handles for each year
    foreach ($years as $year) {
        $tokens[$year] = getGitHubToken();
        $query = buildContributionGraphQuery($user, $year);
        $requests[$year] = getGraphQLCurlHandle($query, $tokens[$year]);
    }
    // build multi-curl handle
    $multi = curl_multi_init();
    foreach ($requests as $handle) {
        curl_multi_add_handle($multi, $handle);
    }
    // execute queries
    $running = null;
    do {
        curl_multi_exec($multi, $running);
    } while ($running);
    // collect responses
    $responses = [];
    foreach ($requests as $year => $handle) {
        $contents = curl_multi_getcontent($handle);
        $decoded = is_string($contents) ? json_decode($contents) : null;
        // if response is empty or invalid, retry request one time or throw an error
        if (empty($decoded) || empty($decoded->data) || !empty($decoded->errors)) {
            $message = $decoded->errors[0]->message ?? ($decoded->message ?? "An API error occurred.");
            $error_type = $decoded->errors[0]->type ?? "";
            // Missing SSL certificate
            if (curl_errno($handle) === 60) {
                throw new AssertionError("You don't have a valid SSL Certificate installed or XAMPP.", 500);
            }
            // Other cURL error
            elseif (curl_errno($handle)) {
                throw new AssertionError("cURL error: " . curl_error($handle), 500);
            }
            // GitHub API error - Not Found
            elseif ($error_type === "NOT_FOUND") {
                throw new InvalidArgumentException("Could not find a user with that name.", 404);
            }
            // if rate limit is exceeded, don't retry with same token
            if (str_contains($message, "rate limit exceeded")) {
                removeGitHubToken($tokens[$year]);
            }
            error_log("First attempt to decode response for $user's $year contributions failed. $message");
            error_log("Contents: $contents");
            // retry request
            $query = buildContributionGraphQuery($user, $year);
            $token = getGitHubToken();
            $request = getGraphQLCurlHandle($query, $token);
            $contents = curl_exec($request);
            $decoded = is_string($contents) ? json_decode($contents) : null;
            // if the response is still empty or invalid, log an error and skip the year
            if (empty($decoded) || empty($decoded->data)) {
                $message = $decoded->errors[0]->message ?? ($decoded->message ?? "An API error occurred.");
                if (str_contains($message, "rate limit exceeded")) {
                    removeGitHubToken($token);
                }
                error_log("Failed to decode response for $user's $year contributions after 2 attempts. $message");
                error_log("Contents: $contents");
                continue;
            }
        }
        $responses[$year] = $decoded;
    }
    // close the handles
    foreach ($requests as $request) {
        curl_multi_remove_handle($multi, $handle);
    }
    curl_multi_close($multi);
    return $responses;
}

/**
 * Get all HTTP request responses for user's contributions
 *
 * @param string $user GitHub username to get graphs for
 * @param int|null $startingYear Override the minimum year to get graphs for
 * @return array<stdClass> List of contribution graph response objects
 */
function getContributionGraphs(string $user, ?int $startingYear = null): array
{
    // get the list of years the user has contributed and the current year's contribution graph
    $currentYear = intval(date("Y"));
    $responses = executeContributionGraphRequests($user, [$currentYear]);
    // get user's created date (YYYY-MM-DDTHH:MM:SSZ format)
    $userCreatedDateTimeString = $responses[$currentYear]->data->user->createdAt ?? null;
    // if there are no contribution years, an API error must have occurred
    if (empty($userCreatedDateTimeString)) {
        throw new AssertionError("Failed to retrieve contributions. This is likely a GitHub API issue.", 500);
    }
    // extract the year from the created datetime string
    $userCreatedYear = intval(explode("-", $userCreatedDateTimeString)[0]);
    // if override parameter is null then define starting year
    // as the user created year; else use the provided override year
    $minimumYear = $startingYear ?: $userCreatedYear;
    // make sure the minimum year is not before 2005 (the year Git was created)
    $minimumYear = max($minimumYear, 2005);
    // create an array of years from the user's created year to one year before the current year
    $yearsToRequest = range($minimumYear, $currentYear - 1);
    // also check the first contribution year if the year is before 2005 (the year Git was created)
    // since the user may have backdated some commits to a specific year such as 1970 (see #448)
    $contributionYears = $responses[$currentYear]->data->user->contributionsCollection->contributionYears ?? [];
    $firstContributionYear = $contributionYears[count($contributionYears) - 1] ?? $userCreatedYear;
    if ($firstContributionYear < 2005) {
        array_unshift($yearsToRequest, $firstContributionYear);
    }
    // get the contribution graphs for the previous years
    $responses += executeContributionGraphRequests($user, $yearsToRequest);
    return $responses;
}

/**
 * Get all tokens from environment variables (TOKEN, TOKEN2, TOKEN3, etc.) if they are set
 *
 * @return array<string> List of tokens
 */
function getGitHubTokens(): array
{
    // result is already calculated
    if (isset($GLOBALS["ALL_TOKENS"])) {
        return $GLOBALS["ALL_TOKENS"];
    }
    // find all tokens in environment variables
    $tokens = isset($_ENV["TOKEN"]) ? [$_ENV["TOKEN"]] : [];
    $index = 2;
    while (isset($_ENV["TOKEN{$index}"])) {
        // add token to list
        $tokens[] = $_ENV["TOKEN{$index}"];
        $index++;
    }
    // store for future use
    $GLOBALS["ALL_TOKENS"] = $tokens;
    return $tokens;
}

/**
 * Get a token from the token pool
 *
 * @return string GitHub token
 *
 * @throws AssertionError if no tokens are available
 */
function getGitHubToken(): string
{
    $all_tokens = getGitHubTokens();
    // if there is no available token, throw an error (this should never happen)
    if (empty($all_tokens)) {
        throw new AssertionError("There is no GitHub token available.", 500);
    }
    return $all_tokens[array_rand($all_tokens)];
}

/**
 * Remove a token from the token pool
 *
 * @param string $token Token to remove
 * @return void
 *
 * @throws AssertionError if no tokens are available after removing the token
 */
function removeGitHubToken(string $token): void
{
    $index = array_search($token, $GLOBALS["ALL_TOKENS"]);
    if ($index !== false) {
        unset($GLOBALS["ALL_TOKENS"][$index]);
    }
    // if there is no available token, throw an error
    if (empty($GLOBALS["ALL_TOKENS"])) {
        throw new AssertionError(
            "We are being rate-limited! Check <a href='https://git.io/streak-ratelimit' font-weight='bold'>git.io/streak-ratelimit</a> for details.",
            429
        );
    }
}

/** Create a CurlHandle for a POST request to GitHub's GraphQL API
 *
 * @param string $query GraphQL query
 * @param string $token GitHub token to use for the request
 * @return CurlHandle The curl handle for the request
 */
function getGraphQLCurlHandle(string $query, string $token): CurlHandle
{
    $headers = [
        "Authorization: bearer $token",
        "Content-Type: application/json",
        "Accept: application/vnd.github.v4.idl",
        "User-Agent: GitHub-Readme-Streak-Stats",
    ];
    $body = ["query" => $query];
    // create curl request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.github.com/graphql");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, false);
    return $ch;
}

/**
 * Get an array of all dates with the number of contributions
 *
 * @param array<int,stdClass> $contributionCalendars List of GraphQL response objects by year
 * @return array<string,int> Y-M-D dates mapped to the number of contributions
 */
function getContributionDates(array $contributionGraphs): array
{
    $contributions = [];
    $today = date("Y-m-d");
    $tomorrow = date("Y-m-d", strtotime("tomorrow"));
    // sort contribution calendars by year key
    ksort($contributionGraphs);
    foreach ($contributionGraphs as $graph) {
        $weeks = $graph->data->user->contributionsCollection->contributionCalendar->weeks;
        foreach ($weeks as $week) {
            foreach ($week->contributionDays as $day) {
                $date = $day->date;
                $count = $day->contributionCount;
                // count contributions up until today
                // also count next day if user contributed already
                if ($date <= $today || ($date == $tomorrow && $count > 0)) {
                    // add contributions to the array
                    $contributions[$date] = $count;
                }
            }
        }
    }
    return $contributions;
}

/**
 * Normalize names of days of the week (eg. ["Sunday", " mon", "TUE"] -> ["Sun", "Mon", "Tue"])
 *
 * @param array<string> $days List of days of the week
 * @return array<string> List of normalized days of the week
 */
function normalizeDays(array $days): array
{
    return array_filter(
        array_map(function ($dayOfWeek) {
            // trim whitespace, capitalize first letter only, return first 3 characters
            $dayOfWeek = substr(ucfirst(strtolower(trim($dayOfWeek))), 0, 3);
            // return day if valid, otherwise return null
            return in_array($dayOfWeek, ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"]) ? $dayOfWeek : null;
        }, $days)
    );
}

/**
 * Check if a day is an excluded day of the week
 *
 * @param string $date Date to check (Y-m-d)
 * @param array<string> $excludedDays List of days of the week to exclude
 * @return bool True if the day is excluded, false otherwise
 */
function isExcludedDay(string $date, array $excludedDays): bool
{
    if (empty($excludedDays)) {
        return false;
    }
    $day = date("D", strtotime($date)); // "D" = Mon, Tue, Wed, etc.
    return in_array($day, $excludedDays);
}

/**
 * Get a stats array with the contribution count, daily streak, and dates
 *
 * @param array<string,int> $contributions Y-M-D contribution dates with contribution counts
 * @param array<string> $excludedDays List of days of the week to exclude
 * @return array<string,mixed> Streak stats
 */
function getContributionStats(array $contributions, array $excludedDays = []): array
{
    // if no contributions, display error
    if (empty($contributions)) {
        throw new AssertionError("No contributions found.", 204);
    }
    $today = array_key_last($contributions);
    $first = array_key_first($contributions);
    $stats = [
        "mode" => "daily",
        "totalContributions" => 0,
        "firstContribution" => "",
        "longestStreak" => [
            "start" => $first,
            "end" => $first,
            "length" => 0,
        ],
        "currentStreak" => [
            "start" => $first,
            "end" => $first,
            "length" => 0,
        ],
        "excludedDays" => $excludedDays,
    ];

    // calculate the stats from the contributions array
    foreach ($contributions as $date => $count) {
        // add contribution count to total
        $stats["totalContributions"] += $count;
        // check if still in streak
        if ($count > 0 || ($stats["currentStreak"]["length"] > 0 && isExcludedDay($date, $excludedDays))) {
            // increment streak
            ++$stats["currentStreak"]["length"];
            $stats["currentStreak"]["end"] = $date;
            // set start on first day of streak
            if ($stats["currentStreak"]["length"] == 1) {
                $stats["currentStreak"]["start"] = $date;
            }
            // set first contribution date the first time
            if (!$stats["firstContribution"]) {
                $stats["firstContribution"] = $date;
            }
            // update longestStreak
            if ($stats["currentStreak"]["length"] > $stats["longestStreak"]["length"]) {
                // copy current streak start, end, and length into longest streak
                $stats["longestStreak"]["start"] = $stats["currentStreak"]["start"];
                $stats["longestStreak"]["end"] = $stats["currentStreak"]["end"];
                $stats["longestStreak"]["length"] = $stats["currentStreak"]["length"];
            }
        }
        // reset streak but give exception for today
        elseif ($date != $today) {
            // reset streak
            $stats["currentStreak"]["length"] = 0;
            $stats["currentStreak"]["start"] = $today;
            $stats["currentStreak"]["end"] = $today;
        }
    }
    return $stats;
}

/**
 * Get the previous Sunday of a given date
 *
 * @param string $date Date to get previous Sunday of (Y-m-d)
 * @return string Previous Sunday
 */
function getPreviousSunday(string $date): string
{
    $dayOfWeek = date("w", strtotime($date));
    return date("Y-m-d", strtotime("-$dayOfWeek days", strtotime($date)));
}

/**
 * Get a stats array with the contribution count, weekly streak, and dates
 *
 * @param array<string,int> $contributions Y-M-D contribution dates with contribution counts
 * @return array<string,mixed> Streak stats
 */
function getWeeklyContributionStats(array $contributions): array
{
    // if no contributions, display error
    if (empty($contributions)) {
        throw new AssertionError("No contributions found.", 204);
    }
    $thisWeek = getPreviousSunday(array_key_last($contributions));
    $first = array_key_first($contributions);
    $firstWeek = getPreviousSunday($first);
    $stats = [
        "mode" => "weekly",
        "totalContributions" => 0,
        "firstContribution" => "",
        "longestStreak" => [
            "start" => $firstWeek,
            "end" => $firstWeek,
            "length" => 0,
        ],
        "currentStreak" => [
            "start" => $firstWeek,
            "end" => $firstWeek,
            "length" => 0,
        ],
    ];

    // calculate contributions per week
    $weeks = [];
    foreach ($contributions as $date => $count) {
        $week = getPreviousSunday($date);
        if (!isset($weeks[$week])) {
            $weeks[$week] = 0;
        }
        if ($count > 0) {
            $weeks[$week] += $count;
            // set first contribution date the first time
            if (!$stats["firstContribution"]) {
                $stats["firstContribution"] = $date;
            }
        }
    }

    // calculate the stats from the contributions array
    foreach ($weeks as $week => $count) {
        // add contribution count to total
        $stats["totalContributions"] += $count;
        // check if still in streak
        if ($count > 0) {
            // increment streak
            ++$stats["currentStreak"]["length"];
            $stats["currentStreak"]["end"] = $week;
            // set start on first week of streak
            if ($stats["currentStreak"]["length"] == 1) {
                $stats["currentStreak"]["start"] = $week;
            }
            // update longestStreak
            if ($stats["currentStreak"]["length"] > $stats["longestStreak"]["length"]) {
                // copy current streak start, end, and length into longest streak
                $stats["longestStreak"]["start"] = $stats["currentStreak"]["start"];
                $stats["longestStreak"]["end"] = $stats["currentStreak"]["end"];
                $stats["longestStreak"]["length"] = $stats["currentStreak"]["length"];
            }
        }
        // reset streak but give exception for this week
        elseif ($week != $thisWeek) {
            // reset streak
            $stats["currentStreak"]["length"] = 0;
            $stats["currentStreak"]["start"] = $thisWeek;
            $stats["currentStreak"]["end"] = $thisWeek;
        }
    }
    return $stats;
}
