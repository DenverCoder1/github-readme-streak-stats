<?php

// load functions
require_once "stats.php";
require_once "card.php";

// load config if the file exists
if (file_exists("config.php")) {
    require_once "config.php";
}
// if environment variables are not loaded, display error
elseif (!getenv("TOKEN")) {
    die(generateErrorCard("/src/config.php was not found. Check Contributing.md for details."));
}

// set cache to refresh once per day
$timestamp = gmdate("D, d M Y 23:59:00") . " GMT";
header("Expires: $timestamp");
header("Last-Modified: $timestamp");
header("Pragma: no-cache");
header("Cache-Control: no-cache, must-revalidate");

// set content type to SVG image
header("Content-Type: image/svg+xml");

// get user from url query string
$user = $_REQUEST["user"];

// redirect to sample site
if(!isset($_REQUEST["user"]){
    header('Location: https://github-readme-streak-stats.herokuapp.com/demo');
    exit;

// get streak stats
$stats = getContributionStats($user);

// echo SVG data for streak stats
echo generateCard($stats);
