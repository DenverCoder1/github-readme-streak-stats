<?php declare (strict_types = 1);

/**
 * Get all HTTP request responses for user's contributions
 *
 * @param string $user GitHub username to get graphs for
 * @return array<string> List of HTML contribution graphs
 */
function getContributionGraphs(string $user): array
{
    // get the start year based on when the user first contributed
    $startYear = getYearJoined($user);
    $currentYear = intval(date("Y"));
    // build a list of individual requests
    $urls = array();
    for ($year = $currentYear; $year >= $startYear; $year--) {
        // create url with year set as end date
        $url = "https://github.com/users/${user}/contributions?to=${year}-12-31";
        // create curl request
        $urls[$year] = curl_init();
        // set options for curl
        curl_setopt($urls[$year], CURLOPT_AUTOREFERER, true);
        curl_setopt($urls[$year], CURLOPT_HEADER, false);
        curl_setopt($urls[$year], CURLOPT_RETURNTRANSFER, true);
        curl_setopt($urls[$year], CURLOPT_URL, $url);
        curl_setopt($urls[$year], CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($urls[$year], CURLOPT_VERBOSE, false);
        curl_setopt($urls[$year], CURLOPT_SSL_VERIFYPEER, true);
    }
    // build multi-curl handle
    $multi = curl_multi_init();
    foreach ($urls as $url) {
        curl_multi_add_handle($multi, $url);
    }
    // execute queries
    $running = null;
    do {
        curl_multi_exec($multi, $running);
    } while ($running);
    // close the handles
    foreach ($urls as $url) {
        curl_multi_remove_handle($multi, $url);
    }
    curl_multi_close($multi);
    // collect responses from last to first
    $response = array();
    foreach ($urls as $url) {
        array_unshift($response, curl_multi_getcontent($url));
    }
    return $response;
}

/**
 * Get an array of all dates with the number of contributions
 *
 * @param array<string> $contributionGraphs List of HTML pages with contributions
 * @return array<string, int> Y-M-D dates mapped to the number of contributions
 */
function getContributionDates(array $contributionGraphs): array
{
    // get contributions from HTML
    $contributions = array();
    $today = date("Y-m-d");
    $tomorrow = date("Y-m-d", strtotime("tomorrow"));
    foreach ($contributionGraphs as $graph) {
        // split into lines
        $lines = explode("\n", $graph);
        // add the dates and contribution counts to the array
        foreach ($lines as $line) {
            preg_match("/ data-date=\"([0-9\-]{10})\"/", $line, $dateMatch);
            preg_match("/ data-count=\"(\d+?)\"/", $line, $countMatch);
            if (isset($dateMatch[1]) && isset($countMatch[1])) {
                $date = $dateMatch[1];
                $count = (int) $countMatch[1];
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
 * Get the contents of a single URL passing headers for GitHub API
 * 
 * @param string $url URL to fetch
 * @return string Response from page as a string
 */
function getGitHubApiResponse(string $url): string
{
    $ch = curl_init();
    $token = getenv("TOKEN");
    $username = getenv("USERNAME");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Accept: application/vnd.github.v3+json",
        "Authorization: token $token",
        "User-Agent: $username",
    ]);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_VERBOSE, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

/**
 * Get the first year a user contributed
 * 
 * @param string $user GitHub username to look up
 * @return int first contribution year
 */
function getYearJoined(string $user): int
{
    // load the user's profile info
    $response = getGitHubApiResponse("https://api.github.com/users/${user}");
    $json = json_decode($response);
    // find the year the user was created
    if ($json && isset($json->type) && $json->type == "User" && isset($json->created_at)) {
        return intval(substr($json->created_at, 0, 4));
    }
    // Account is not a user (eg. Organization account)
    if (isset($json->type)) {
        throw new InvalidArgumentException("The username given is not a user.");
    }
    // API Error
    if ($json && isset($json->message)) {
        // User not found
        if ($json->message == "Not Found") {
            throw new InvalidArgumentException("User could not be found.");
        }
        // Other errors that contain a message field
        throw new InvalidArgumentException($json->message);
    }
    // Response doesn't contain a message field
    throw new InvalidArgumentException("An unknown error occurred.");
}

/**
 * Get a stats array with the contribution count, streak, and dates
 * 
 * @param array<string, int> $contributions Y-M-D contribution dates with contribution counts
 * @return array<string, mixed> Streak stats
 */
function getContributionStats(array $contributions): array
{
    $today = array_key_last($contributions);
    $stats = [
        "totalContributions" => 0,
        "firstContribution" => "",
        "longestStreak" => [
            "start" => array_key_first($contributions),
            "end" => array_key_first($contributions),
            "length" => 0,
        ],
        "currentStreak" => [
            "start" => array_key_first($contributions),
            "end" => array_key_first($contributions),
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
