<?php

// get all http requests for contributions
function getContributionGraphs($user): array
{
    // get the start year based on when the user first contributed
    $startYear = getYearJoined($user);
    $currentYear = (int) date("Y");
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

// get array of all dates with the number of contributions
function getContributionDates($user): array
{
    // fetch html for all contribution graphs
    $contributionsHTML = getContributionGraphs($user);
    // get contributions from HTML
    $contributions = array();
    $currentDate = date("Y-m-d");
    foreach ($contributionsHTML as $html) {
        // split into lines
        $lines = explode("\n", $html);
        // add the dates and contribution counts to the array
        foreach ($lines as $line) {
            preg_match("/ data-date=\"([0-9\-]{10})\"/", $line, $dateMatch);
            preg_match("/ data-count=\"(\d+?)\"/", $line, $countMatch);
            if (isset($dateMatch[1]) && isset($countMatch[1])) {
                $date = $dateMatch[1];
                $count = (int) $countMatch[1];
                if ($date <= $currentDate) {
                    // add contributions to the array
                    $contributions[$date] = $count;
                }
            }
        }
    }
    return $contributions;
}

// Get the contents of a single URL
function curl_get_contents($url): string
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

// get the first year a user contributed
function getYearJoined($user): int
{
    // load the user's profile info
    $response = curl_get_contents("https://api.github.com/users/${user}");
    $json = json_decode($response);
    // find the year the user was created
    if ($json && isset($json->created_at)) {
        return substr($json->created_at, 0, 4);
    }
    // API Error
    else if ($json && isset($json->message)) {
        // User not found
        if ($json->message == "Not Found") {
            die(generateErrorCard("User could not be found."));
        }
        // Other errors that contain a message field
        else {
            die(generateErrorCard($json->message));
        }
    }
    // Response doesn't contain a message field
    else {
        die(generateErrorCard("An unknown error occurred."));
    }
}

// get the total number of contributions
function getContributionStats($user): array
{
    $contributions = getContributionDates($user);
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
        }
    }
    return $stats;
}
