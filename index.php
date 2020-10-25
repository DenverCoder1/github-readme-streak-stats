<?php

require_once "vendor/autoload.php";
require_once "card.php";
use PHPHtmlParser\Dom;

// set cache to refresh once per day
$timestamp = gmdate("D, d M Y 23:59:00") . " GMT";
header("Expires: $timestamp");
header("Last-Modified: $timestamp");
header("Pragma: no-cache");
header("Cache-Control: no-cache, must-revalidate");

// get array of all dates with the number of contributions
function getContributionDates($user) : array
{
    $dom = new Dom;
    $startYear = getYearJoined($user);
    $currentYear = (int) date("Y");
    $contributions = array();
    // check contributions for every year
    for ($i = $startYear; $i <= $currentYear; $i++) {
        // set end date
        $to = date("${i}-m-d");
        // load contributions graph
        $dom->loadFromUrl("https://github.com/users/${user}/contributions?to=${to}");
        // find rectangles in contributions graph
        $rectangles = $dom->find("rect");
        // add the dates and contribution counts to the array
        foreach ($rectangles as $rect) {
            $date = $rect->getAttribute("data-date");
            $count = (int) $rect->getAttribute("data-count");
            $contributions[$date] = $count;
        }
    }
    return $contributions;
}

// get the first year a user contributed
function getYearJoined($user) : int
{
    $dom = new Dom;
    // load the user's profile page
    $dom->loadFromUrl("https://github.com/${user}");
    // find the year links below the contribution graph
    $years = $dom->find("a.js-year-link");
    // check that user page is working
    if (count($years) > 0) {
        // get the inner text of the last year on the page
        return (int) $years[count($years) - 1]->innerText;
    }
    // no user page found at the URL
    else {
        die("User does not exist.");
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

$user = $_REQUEST["user"];

$stats = getContributionStats($user);

header("Content-Type: image/svg+xml");

echo generateCard($stats);
