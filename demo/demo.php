<?php

require_once("../src/card.php");

// set cache to refresh once per day
$timestamp = gmdate('D, d M Y H:i:s') . " GMT";
header("Expires: $timestamp");
header("Last-Modified: $timestamp");
header("Pragma: no-cache");
header("Cache-Control: no-cache, must-revalidate");

// set content type to SVG image
header("Content-Type: image/svg+xml");

// generate demo stats
$demoStats = [
    "totalContributions" => 2048,
    "firstContribution" => "2016-08-10",
    "longestStreak" => [
        "start" => "2020-12-19",
        "end" => "2021-03-14",
        "length" => 86,
    ],
    "currentStreak" => [
        "start" => date("Y-m-d", strtotime("-15 days")),
        "end" => date("Y-m-d"),
        "length" => 16,
    ],
];

// echo SVG data for demo stats
echo generateCard($demoStats);