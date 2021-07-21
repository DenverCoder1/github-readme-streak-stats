<?php declare (strict_types = 1);

// load functions
require_once "stats.php";
require_once "card.php";

// load config if the file exists
if (file_exists("config.php")) {
    require_once "config.php";
}
// if environment variables are not loaded, display error
if (!getenv("TOKEN") || !getenv("USERNAME")) {
    $message = file_exists("config.php")
    ? "Missing token or username in config. Check Contributing.md for details."
    : "src/config.php was not found. Check Contributing.md for details.";
    die(generateErrorCard($message));
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
    $stats = getContributionStats($contributions);
} catch (InvalidArgumentException $error) {
    die(generateErrorCard($error->getMessage()));
}

if (isset($_REQUEST["type"]) && $_REQUEST["type"] === "json") {
    // set content type to JSON
    header('Content-Type: application/json');
    // echo JSON data for streak stats
    echo json_encode($stats);
    // exit
    exit;
}

// set content type to SVG image
header("Content-Type: image/svg+xml");

// echo SVG data for streak stats
echo generateCard($stats);