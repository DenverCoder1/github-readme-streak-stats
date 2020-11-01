<?php

// get theme colors given a theme name
function getTheme($theme): array
{
    $themes = array(
        "default" => [
            "background" => "#fffefe",
            "stroke" => "#e4e2e2",
            "titleText" => "#151515",
            "subtitleText" => "#464646",
            "highlight" => "#fb8c00"
        ],
        "dark" => [
            "background" => "#151515",
            "stroke" => "#e4e2e2",
            "titleText" => "#fefefe",
            "subtitleText" => "#9e9e9e",
            "highlight" => "#fb8c00"
        ],
        "highcontrast" => [
            "background" => "#000000",
            "stroke" => "#bebebe",
            "titleText" => "#ffffff",
            "subtitleText" => "#c5c5c5",
            "highlight" => "#fb8c00"
        ]
    );

    // if offset is valid
    if (isset($themes[$theme])) {
        return $themes[$theme];
    }

    // default theme
    return $themes["default"];
}
