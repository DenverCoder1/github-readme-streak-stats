<?php declare (strict_types = 1);


// load functions
require_once '../vendor/autoload.php';
require_once "stats.php";
require_once "card.php";

// load .env

$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1));
$dotenv->safeLoad();

$requestedType = $_REQUEST['type'] ?? 'svg';

// if environment variables are not loaded, display error
if (!$_SERVER["TOKEN"] || !$_SERVER["USERNAME"]) {
    $message = file_exists(dirname(__DIR__.'.env',1))
    ? "Missing token or username in config. Check Contributing.md for details."
    : ".env was not found. Check Contributing.md for details.";

    if ($requestedType === "json") {
        // set content type to JSON
        header('Content-Type: application/json');
        // echo JSON error message
        echo json_encode(array("error" => $message));
        exit;
    }
    
    $card = generateErrorCard($message);
    if ($requestedType === "png") {
        echoAsPng($card);
    }
    echoAsSvg($card);

    exit;
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
    if ($requestedType === "json") {
        // set content type to JSON
        header('Content-Type: application/json');
        // echo JSON error message
        echo json_encode(array("error" => $error->getMessage()));
        exit;
    }
    $card = generateErrorCard($error->getMessage());
    if ($requestedType === "png") {
        echoAsPng($card);
    }
    echoAsSvg($card);

    exit;
}

if ($requestedType === "json") {
    // set content type to JSON
    header('Content-Type: application/json');
    // echo JSON data for streak stats
    echo json_encode($stats);
    // exit
    exit;
}

$card = generateCard($stats);
if ($requestedType === "png") {
    echoAsPng($card);
}
echoAsSvg($card);
