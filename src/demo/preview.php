<?php

declare(strict_types=1);

require_once "../card.php";
require_once "../stats.php";

$mode = $_GET["mode"] ?? "daily";

// generate demo stats
$demoStats = [
    "mode" => "daily",
    "totalContributions" => 2048,
    "firstContribution" => "2016-08-10",
    "longestStreak" => [
        "start" => "2021-12-19",
        "end" => "2022-03-14",
        "length" => 86,
    ],
    "currentStreak" => [
        "start" => date("Y-m-d", strtotime("-15 days")),
        "end" => date("Y-m-d"),
        "length" => 16,
    ],
];

if ($mode == "weekly") {
    $demoStats["mode"] = "weekly";
    $demoStats["longestStreak"] = [
        "start" => "2021-12-19",
        "end" => "2022-03-13",
        "length" => 13,
    ];
    $demoStats["currentStreak"] = [
        "start" => getPreviousSunday(date("Y-m-d", strtotime("-15 days"))),
        "end" => getPreviousSunday(date("Y-m-d")),
        "length" => 3,
    ];
}

// set content type to SVG image
header("Content-Type: image/svg+xml");

try {
    renderOutput($demoStats);
} catch (InvalidArgumentException | AssertionError $error) {
    error_log("Error {$error->getCode()}: {$error->getMessage()}");
    if ($error->getCode() >= 500) {
        error_log($error->getTraceAsString());
    }
    renderOutput($error->getMessage(), $error->getCode());
}
