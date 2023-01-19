<?php
// Set the content type and return the contents of a static file
// eg $uri = "/demo/css/style.css"
// content type => text/css
// require __DIR__ . "/css/style.css"

// Remove the "/demo" from the URI and the query string
$path = str_replace("/demo", "", strtok($_SERVER["REQUEST_URI"], "?"));
// Get the file extension
$extension = pathinfo($path, PATHINFO_EXTENSION);

// Set the content type based on the file extension
switch ($extension) {
    case "css":
        header("Content-Type: text/css");
        break;
    case "js":
        header("Content-Type: text/javascript");
        break;
    case "svg":
        header("Content-Type: image/svg+xml");
        break;
    case "png":
        header("Content-Type: image/png");
        break;
    default:
        break;
}

// Return the contents of the file
require __DIR__ . $path;
