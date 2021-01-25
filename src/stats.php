<?php

// get all http requests for contributions
function getContributionGraphs($user): array
{
    // get the start year based on when the user first contributed
    $startYear = getYearJoined($user);
    $currentYear = (int) date("Y");
    // build a list of individual requests
    $urls = [];
    for ($i = $currentYear; $i >= $startYear; $i--) {
        // set end date (leave parameter out for current year)
        $to = $i < $currentYear ? "?to=" . date("${i}-m-d") : "";
        // create curl request
        $urls[$i] = curl_init();
        // set options for curl
        curl_setopt($urls[$i], CURLOPT_AUTOREFERER, true);
        curl_setopt($urls[$i], CURLOPT_HEADER, false);
        curl_setopt($urls[$i], CURLOPT_RETURNTRANSFER, true);
        curl_setopt($urls[$i], CURLOPT_URL, "https://github.com/users/${user}/contributions${to}");
        curl_setopt($urls[$i], CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($urls[$i], CURLOPT_VERBOSE, false);
        curl_setopt($urls[$i], CURLOPT_SSL_VERIFYPEER, false);
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
    //close the handles
    foreach ($urls as $url) {
        curl_multi_remove_handle($multi, $url);
    }
    curl_multi_close($multi);
    // collect responses
    $response = [];
    foreach ($urls as $url) {
        array_unshift($response, curl_multi_getcontent($url));
    }
    return $response;
}

// get array of all dates with the number of contributions
function getContributionDates($user) : array
{
    // fetch html for all contribution graphs
    $contributionsHTML = getContributionGraphs($user);
    // get contributions from HTML
    $contributions = [];
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
                $contributions[$date] = $count;
            }
        }
    }
    return $contributions;
}

// Get the contents of a single URL
function curl_get_contents($url): string
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_VERBOSE, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

// get the first year a user contributed
function getYearJoined($user) : int
{
    // load the user's profile page
    $html = curl_get_contents("https://github.com/${user}");
    // find the year links below the contribution graph
    $years = [];
    $lines = explode("\n", $html);
    foreach ($lines as $line) {
        if (strpos($line, "id=\"year-link-") !== false) {
            preg_match("/year-link-(\d{4})/", $line, $yearMatch);
            if (isset($yearMatch[1])) {
                array_push($years, (int) $yearMatch[1]);
            }
        }
    }
    // check that user page is working
    if (count($years) > 0) {
        // get the inner text of the last year on the page
        return $years[count($years) - 1];
    }
    // no user page found at the URL
    else {
        // TODO: make error appear in an SVG so users can see it
        die("User info could not be found.");
    }
}

// get the total number of contributions
function getContributionStats($user) : array
{
    $contributions = getContributionDates($user);
    $today = array_key_last($contributions);
    $stats = [
        "totalContributions" => 0,
        "firstContribution" => "",
        "longestStreak" => [
            "start" => array_key_first($contributions),
            "end" => array_key_first($contributions),
            "length" => 0
        ],
        "currentStreak" => [
            "start" => array_key_first($contributions),
            "end" => array_key_first($contributions),
            "length" => 0
        ]
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
            // first day of streak
            if ($stats["currentStreak"]["length"] == 1) {
                $stats["currentStreak"]["start"] = $date;
            }
            // first contribution
            if ($stats["firstContribution"] == "") {
                $stats["firstContribution"] = $date;
            }
            // update longestStreak
            if ($stats["currentStreak"]["length"] > $stats["longestStreak"]["length"]) {
                $stats["longestStreak"]["length"] = $stats["currentStreak"]["length"];
                $stats["longestStreak"]["start"] = $stats["currentStreak"]["start"];
                $stats["longestStreak"]["end"] = $stats["currentStreak"]["end"];
            }
        }
        // reset streak but give exception for today
        elseif ($date != $today) {
            // reset streak
            $stats["currentStreak"]["length"] = 0;
            $stats["currentStreak"]["start"] = "";
            $stats["currentStreak"]["end"] = "";
        }
    }
    return $stats;
}
