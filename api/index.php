<?php

declare(strict_types=1);

// load functions
require_once dirname(__DIR__, 1) . "/vendor/autoload.php";
require_once "stats.php";
require_once "card.php";
require_once "cache.php";

// load .env
$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1));
$dotenv->safeLoad();

// if environment variables are not loaded, display error
if (!isset($_ENV["TOKEN"])) {
    $message = file_exists(dirname(__DIR__, 1) . "/.env")
        ? "Missing token in config. Check Contributing.md for details."
        : ".env was not found. Check Contributing.md for details.";
    renderOutput($message, 500);
}

// set cache to refresh once per day (24 hours)
$cacheSeconds = CACHE_DURATION;
header("Expires: " . gmdate("D, d M Y H:i:s", time() + $cacheSeconds) . " GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: public, max-age=$cacheSeconds");

// redirect to demo site if user is not given
if (!isset($_REQUEST["user"])) {
    header("Location: demo/");
    exit();
}

try {
    // get streak stats for user given in query string
    $user = preg_replace("/[^a-zA-Z0-9\-]/", "", $_REQUEST["user"]);
    $startingYear = isset($_REQUEST["starting_year"]) ? intval($_REQUEST["starting_year"]) : null;
    $mode = isset($_GET["mode"]) ? $_GET["mode"] : null;
    $excludeDaysRaw = $_GET["exclude_days"] ?? "";

    // Build cache options based on request parameters
    $cacheOptions = [
        "starting_year" => $startingYear,
        "mode" => $mode,
        "exclude_days" => $excludeDaysRaw,
    ];

    // Check if cache is disabled
    $useCache = !isset($_SERVER["DISABLE_CACHE"]) || strtolower($_SERVER["DISABLE_CACHE"]) !== "true";

    // Check for cached stats first (24 hour cache) unless cache is disabled
    $cachedStats = $useCache ? getCachedStats($user, $cacheOptions) : null;

    if ($cachedStats !== null) {
        // Use cached stats - instant response!
        $stats = $cachedStats;
    } else {
        // Fetch fresh data from GitHub API
        $contributionGraphs = getContributionGraphs($user, $startingYear);
        $contributions = getContributionDates($contributionGraphs);

        if ($mode === "weekly") {
            $stats = getWeeklyContributionStats($contributions);
        } else {
            // split and normalize excluded days
            $excludeDays = normalizeDays(explode(",", $excludeDaysRaw));
            $stats = getContributionStats($contributions, $excludeDays);
        }

        // Cache the stats for 24 hours unless cache is disabled
        if ($useCache) {
            setCachedStats($user, $cacheOptions, $stats);
        }
    }

    renderOutput($stats);
} catch (InvalidArgumentException | AssertionError $error) {
    error_log("Error {$error->getCode()}: {$error->getMessage()}");
    if ($error->getCode() >= 500) {
        error_log($error->getTraceAsString());
    }
    renderOutput($error->getMessage(), $error->getCode());
}
