<?php

declare(strict_types=1);


// load functions
require_once '../vendor/autoload.php';
require_once "stats.php";
require_once "card.php";

// load .env

$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1));
$dotenv->safeLoad();

// if environment variables are not loaded, display error
if (!$_SERVER["TOKEN"] || !$_SERVER["USERNAME"]) {
    $message = file_exists(dirname(__DIR__ . '.env', 1))
        ? "Missing token or username in config. Check Contributing.md for details."
        : ".env was not found. Check Contributing.md for details.";

    renderOutput($message);
}


// set cache to refresh once per day
$timestamp = gmdate("D, d M Y 23:59:00") . " GMT";
header("Expires: $timestamp");
header("Last-Modified: $timestamp");
header("Pragma: no-cache");
header("Cache-Control: no-cache, must-revalidate");

// redirect to demo site if user is not given
if (!isset($_REQUEST["user"])) {
    header('Location: demo/');
    exit;
}

try {
    // get streak stats for user given in query string
    $contributionGraphs = getContributionGraphs($_REQUEST["user"]);
    $contributions = getContributionDates($contributionGraphs);
    // if no contributions, display error
    if (count($contributions) === 0) {
        throw new AssertionError("No contributions found.");
    }
    $stats = getContributionStats($contributions);
    renderOutput($stats);
} catch (InvalidArgumentException|AssertionError $error) {
    renderOutput($error->getMessage());
}
