<?php

declare(strict_types=1);

/**
 * Get all HTTP request responses for user's contributions
 *
 * @param string $user GitHub username to get graphs for
 *
 * @return array<stdClass> List of contribution graph response objects
 */
function getContributionGraphs(string $user): array
{
    // Get the years the user has contributed
    $contributionYears = getContributionYears($user);
    // build a list of individual requests
    $requests = [];
    foreach ($contributionYears as $year) {
        // create query for year
        $start = "$year-01-01T00:00:00Z";
        $end = "$year-12-31T23:59:59Z";
        $query = "query {
            user(login: \"$user\") {
                contributionsCollection(from: \"$start\", to: \"$end\") {
                    contributionCalendar {
                        totalContributions
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
        // create curl request
        $requests[$year] = getGraphQLCurlHandle($query);
    }
    // build multi-curl handle
    $multi = curl_multi_init();
    foreach ($requests as $request) {
        curl_multi_add_handle($multi, $request);
    }
    // execute queries
    $running = null;
    do {
        curl_multi_exec($multi, $running);
    } while ($running);
    // close the handles
    foreach ($requests as $request) {
        curl_multi_remove_handle($multi, $request);
    }
    curl_multi_close($multi);
    // collect responses from last to first
    $response = [];
    foreach ($requests as $request) {
        $contents = curl_multi_getcontent($request);
        $decoded = json_decode($contents);
        if (empty($decoded)) {
            throw new Exception("Empty response from GitHub GraphQL API: " . $contents);
        }
        array_unshift($response, $decoded);
    }
    return $response;
}

/**
 * Get all tokens from environment variables (TOKEN, TOKEN2, TOKEN3, etc.) if they are set
 *
 * @return array<string> List of tokens
 */
function getGitHubTokens()
{
    // result is already calculated
    if (isset($GLOBALS["ALL_TOKENS"])) {
        return $GLOBALS["ALL_TOKENS"];
    }
    // find all tokens in environment variables
    $tokens = isset($_SERVER["TOKEN"]) ? [$_SERVER["TOKEN"]] : [];
    $index = 2;
    while (isset($_SERVER["TOKEN{$index}"])) {
        // add token to list
        $tokens[] = $_SERVER["TOKEN{$index}"];
        $index++;
    }
    // store for future use
    $GLOBALS["ALL_TOKENS"] = $tokens;
    return $tokens;
}

/** Create a CurlHandle for a POST request to GitHub's GraphQL API
 *
 * @param string $query GraphQL query
 *
 * @return CurlHandle The curl handle for the request
 */
function getGraphQLCurlHandle(string $query)
{
    $all_tokens = getGitHubTokens();
    $token = $all_tokens[array_rand($all_tokens)];
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
 * Create a POST request to GitHub's GraphQL API
 *
 * @param string $query GraphQL query
 *
 * @return stdClass An object from the json response of the request
 *
 * @throws AssertionError If SSL verification fails
 */
function fetchGraphQL(string $query): stdClass
{
    $ch = getGraphQLCurlHandle($query);
    $response = curl_exec($ch);
    curl_close($ch);
    $obj = json_decode($response);
    // handle curl errors
    if ($response === false || $obj === null || curl_getinfo($ch, CURLINFO_HTTP_CODE) >= 400) {
        // set response code to curl error code
        http_response_code(curl_getinfo($ch, CURLINFO_HTTP_CODE));
        // Missing SSL certificate
        if (str_contains(curl_error($ch), "unable to get local issuer certificate")) {
            throw new AssertionError("You don't have a valid SSL Certificate installed or XAMPP.", 400);
        }
        // Handle errors such as "Bad credentials"
        if ($obj && $obj->message) {
            throw new AssertionError("Error: $obj->message \n<!-- $response -->", 401);
        }
        throw new AssertionError("An error occurred when getting a response from GitHub.\n<!-- $response -->", 502);
    }
    return $obj;
}

/**
 * Get the years the user has contributed
 *
 * @param string $user GitHub username to get years for
 *
 * @return array List of years the user has contributed
 *
 * @throws InvalidArgumentException If the user doesn't exist or there is an error
 */
function getContributionYears(string $user): array
{
    $query = "query {
        user(login: \"$user\") {
            contributionsCollection {
                contributionYears
            }
        }
    }";
    $response = fetchGraphQL($query);
    // User not found
    if (!empty($response->errors) && $response->errors[0]->type === "NOT_FOUND") {
        throw new InvalidArgumentException("Could not find a user with that name.", 404);
    }
    // API Error
    if (!empty($response->errors)) {
        // Other errors that contain a message field
        throw new InvalidArgumentException($response->errors[0]->message, 500);
    }
    // API did not return data
    if (!isset($response->data) && isset($response->message)) {
        // Other errors that contain a message field
        throw new InvalidArgumentException($response->message, 204);
    }
    return $response->data->user->contributionsCollection->contributionYears;
}

/**
 * Get an array of all dates with the number of contributions
 *
 * @param array<string> $contributionCalendars List of GraphQL response objects
 *
 * @return array<string, int> Y-M-D dates mapped to the number of contributions
 */
function getContributionDates(array $contributionGraphs): array
{
    // get contributions from HTML
    $contributions = [];
    $today = date("Y-m-d");
    $tomorrow = date("Y-m-d", strtotime("tomorrow"));
    foreach ($contributionGraphs as $graph) {
        if (!empty($graph->errors)) {
            throw new AssertionError($graph->data->errors[0]->message, 502);
        }
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
 * Get a stats array with the contribution count, daily streak, and dates
 *
 * @param array<string, int> $contributions Y-M-D contribution dates with contribution counts
 * @return array<string, mixed> Streak stats
 */
function getContributionStats(array $contributions): array
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
    ];

    // calculate the stats from the contributions array
    foreach ($contributions as $date => $count) {
        // add contribution count to total
        $stats["totalContributions"] += $count;
        // check if still in streak
        if ($count > 0) {
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
 * @param array<string, int> $contributions Y-M-D contribution dates with contribution counts
 * @return array<string, mixed> Streak stats
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
