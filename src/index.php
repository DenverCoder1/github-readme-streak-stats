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
    $contributionGraphs = getContributionGraphs($_REQUEST["user"]);
    $contributions = getContributionDates($contributionGraphs);
    $stats = getContributionStats($contributions);
    renderOutput($stats);
} catch (InvalidArgumentException | AssertionError $error) {
    renderOutput($error->getMessage(), $error->getCode());
}
