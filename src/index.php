<?php

declare(strict_types=1);

// load functions
require_once "../vendor/autoload.php";
require_once "stats.php";
require_once "card.php";
require_once "cache.php";
require_once "generator.php";

// load .env
$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1));
$dotenv->safeLoad();

// if environment variables are not loaded, display error
if (!isset($_SERVER["TOKEN"])) {
    $message = file_exists(dirname(__DIR__ . "../.env", 1))
        ? "Missing token in config. Check Contributing.md for details."
        : ".env was not found. Check Contributing.md for details.";
    // Avoid Camo keeping a config error for a full day (#944)
    setHttpCacheHeaders(SHORT_CACHE_DURATION);
    renderOutput($message, 500);
}

// redirect to demo site if user is not given
if (!isset($_REQUEST["user"])) {
    header("Location: demo/");
    exit();
}

try {
    $timezone = isset($_REQUEST["timezone"]) ? strval($_REQUEST["timezone"]) : "";
    $stats = generateStreakStats($_REQUEST["user"], $_REQUEST);
    // Set Cache-Control after stats are known so HTTP lifetime matches freshness (#944)
    setHttpCacheHeaders(getHttpCacheSeconds($stats, $timezone));
    renderOutput($stats);
} catch (InvalidArgumentException | AssertionError $error) {
    error_log("Error {$error->getCode()}: {$error->getMessage()}");
    if ($error->getCode() >= 500) {
        error_log($error->getTraceAsString());
    }
    // Short lifetime for error cards (HTTP 200) so temporary failures expire (#944)
    setHttpCacheHeaders(SHORT_CACHE_DURATION);
    renderOutput($error->getMessage(), $error->getCode());
}
