<?php

require_once "stats.php";
require_once "card.php";

// set cache to refresh once per day
$timestamp = gmdate("D, d M Y 23:59:00") . " GMT";
header("Expires: $timestamp");
header("Last-Modified: $timestamp");
header("Pragma: no-cache");
header("Cache-Control: no-cache, must-revalidate");

// get user from url query string
$user = $_REQUEST["user"];

// get streak stats
$stats = getContributionStats($user);

// set content type to SVG image
header("Content-Type: image/svg+xml");

// echo SVG data for streak stats
echo generateCard($stats);
