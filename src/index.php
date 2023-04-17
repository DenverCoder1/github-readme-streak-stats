<?php

declare(strict_types=1);

// load functions
require_once "../vendor/autoload.php";
require_once "stats.php";
require_once "card.php";

// load .env
$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1));
$dotenv->safeLoad();

// if environment variables are not loaded, display error
if (!isset($_SERVER["TOKEN"])) {
    $message = file_exists(dirname(__DIR__ . "../.env", 1))
        ? "Missing token in config. Check Contributing.md for details."
        : ".env was not found. Check Contributing.md for details.";
    renderOutput($message, 500);
}

// set cache to refresh once per hour
header("Expires: " . gmdate("D, d M Y H:i:s", time() + 3600) . " GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: public, max-age=3600");

// redirect to demo site if user is not given
if (!isset($_REQUEST["user"])) {
    header("Location: demo/");
    exit();
}

try {
    // get streak stats for user given in query string
    $user = preg_replace("/[^a-zA-Z0-9\-]/", "", $_REQUEST["user"]);
    $contributionGraphs = getContributionGraphs($user);
    $contributions = getContributionDates($contributionGraphs);
    if (isset($_GET["mode"]) && $_GET["mode"] === "weekly") {
        $stats = getWeeklyContributionStats($contributions);
    } else {
        // split and normalize excluded days - trim whitespace, capitalize first letter only, return first 3 characters
        $excludeDays = array_filter(
            array_map(function ($dayOfWeek) {
                $dayOfWeek = substr(ucfirst(strtolower(trim($dayOfWeek))), 0, 3);
                return in_array($dayOfWeek, ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"]) ? $dayOfWeek : null;
            }, explode(",", $_GET["exclude_days"] ?? ""))
        );
        $stats = getContributionStats($contributions, $excludeDays);
    }
    renderOutput($stats);
} catch (InvalidArgumentException | AssertionError $error) {
    error_log("Error {$error->getCode()}: {$error->getMessage()}");
    if ($error->getCode() >= 500) {
        error_log($error->getTraceAsString());
    }
    renderOutput($error->getMessage(), $error->getCode());
}
