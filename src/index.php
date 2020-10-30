<?php

require_once "card.php";

// set cache to refresh once per day
$timestamp = gmdate("D, d M Y 23:59:00") . " GMT";
header("Expires: $timestamp");
header("Last-Modified: $timestamp");
header("Pragma: no-cache");
header("Cache-Control: no-cache, must-revalidate");

$GLOBALS['contributions'] = array();

$user = $_REQUEST["user"];

$stats = getContributionStats($user);

header("Content-Type: image/svg+xml");

echo generateCard($stats);

// get all http requests for contributions
function getContributionGraphs($user): void
{
    $startYear = getYearJoined($user);
    $currentYear = (int) date("Y");
    // build a list of individual requests
    $urls = [];
    for ($i = $startYear; $i <= $currentYear; $i++) {
        // set end date (leave parameter out for current year)
        $to = $i < $currentYear ? "?to=" . date("${i}-m-d") : "";
        $urls[$i] = curl_init();
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
    foreach ($urls as $url) {
        pushContributions(curl_multi_getcontent($url));
    }
}

// get contributions from HTML
function pushContributions($html): void
{
    // split into lines
    $lines = explode("\n", $html);
    // add the dates and contribution counts to the array
    foreach ($lines as $line) {
        preg_match("/ data-date=\"([0-9\-]{10})\"/", $line, $dateMatch);
        preg_match("/ data-count=\"(\d+?)\"/", $line, $countMatch);
        if (isset($dateMatch[1]) && isset($countMatch[1])) {
            $date = $dateMatch[1];
            $count = (int) $countMatch[1];
            $GLOBALS['contributions'][$date] = $count;
        }
    }
}

// get array of all dates with the number of contributions
function getContributionDates($user) : array
{
    getContributionGraphs($user);
    return $GLOBALS['contributions'];
}

// Get the contents of a URL
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
        if (strpos($line, "id=\"year-link-2") !== false) {
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
