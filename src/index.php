<?php declare (strict_types = 1);

// load functions
require_once "stats.php";
require_once "card.php";

// load config if the file exists
if (file_exists("config.php")) {
    require_once "config.php";
}

$requestedType = $_REQUEST['type'] ?? 'svg';

// if environment variables are not loaded, display error
if (!getenv("TOKEN") || !getenv("USERNAME")) {
    $message = file_exists("config.php")
    ? "Missing token or username in config. Check Contributing.md for details."
    : "src/config.php was not found. Check Contributing.md for details.";


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


function echoAsSvg($svg) {
    // set content type to SVG image
    header("Content-Type: image/svg+xml");

    // echo SVG data for streak stats
    echo $svg;
}

function echoAsPng($svg) {
    // remove style and animations
    $svg = preg_replace('/(<style>\X*<\/style>)/m', '', $svg);
    $svg = preg_replace('/(opacity: 0;)/m', 'opacity: 1;', $svg);
    $svg = preg_replace('/(animation: fadein.*?;)/m', 'opacity: 1;', $svg);
    $svg = preg_replace('/(animation: currentstreak.*?;)/m', 'font-size: 28px;', $svg);

    // create canvas
    $imagick = new Imagick();
    $imagick->setBackgroundColor(new ImagickPixel('transparent'));

    // add svg image
    $imagick->readImageBlob($svg);
    $imagick->setImageFormat('png');

    // echo PNG data
    header('Content-Type: image/png');
    echo $imagick->getImageBlob();

    // clean up memory
    $imagick->clear();
    $imagick->destroy();
}
