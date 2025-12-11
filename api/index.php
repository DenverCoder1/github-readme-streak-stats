<?php

declare(strict_types=1);

// Load functions with absolute paths for Vercel
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../src/stats.php";
require_once __DIR__ . "/../src/card.php";

// Load .env
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->safeLoad();

// If environment variables are not loaded, display error
if (!isset($_SERVER["TOKEN"]) && !isset($_ENV["TOKEN"])) {
    $message = "Missing TOKEN environment variable. Please set it in Vercel project settings.";
    renderOutput($message, 500);
    exit();
}

// Use environment variable if not in $_SERVER
if (!isset($_SERVER["TOKEN"]) && isset($_ENV["TOKEN"])) {
    $_SERVER["TOKEN"] = $_ENV["TOKEN"];
}

// Set cache to refresh once per three hours
$cacheMinutes = 3 * 60 * 60;
header("Expires: " . gmdate("D, d M Y H:i:s", time() + $cacheMinutes) . " GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: public, max-age=$cacheMinutes");

// Redirect to demo site if user is not given
if (!isset($_REQUEST["user"])) {
    header("Location: /demo/");
    exit();
}

try {
    // Get streak stats for user given in query string
    $user = preg_replace("/[^a-zA-Z0-9\-]/", "", $_REQUEST["user"]);
    $startingYear = isset($_REQUEST["starting_year"]) ? intval($_REQUEST["starting_year"]) : null;
    $contributionGraphs = getContributionGraphs($user, $startingYear);
    $contributions = getContributionDates($contributionGraphs);
    if (isset($_GET["mode"]) && $_GET["mode"] === "weekly") {
        $stats = getWeeklyContributionStats($contributions);
    } else {
        // Split and normalize excluded days
        $excludeDays = normalizeDays(explode(",", $_GET["exclude_days"] ?? ""));
        $stats = getContributionStats($contributions, $excludeDays);
    }
    renderOutput($stats);
} catch (InvalidArgumentException | AssertionError $error) {
    error_log("Error {$error->getCode()}: {$error->getMessage()}");
    if ($error->getCode() >= 500) {
        renderOutput("Unable to fetch contribution data. Please try again later.", 500);
    } else {
        renderOutput($error->getMessage(), $error->getCode());
    }
} catch (Exception $error) {
    error_log("Unexpected error: " . $error->getMessage());
    renderOutput("An unexpected error occurred. Please try again later.", 500);
}
