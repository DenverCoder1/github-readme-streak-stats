<?php declare (strict_types = 1);

require_once "../card.php";

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

// set content type to SVG image
header("Content-Type: image/svg+xml");

// echo SVG data for demo stats
echo generateCard($demoStats);
