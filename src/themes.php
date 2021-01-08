<?php

// get theme colors given a theme name
function getTheme($theme): array
{
    $themes = array(
        "default" => [
            "background" => "#fffefe",
            "stroke" => "#e4e2e2",
            "titleText" => "#151515",
            "currentStreak" => "#151515",
            "ring" => "#fb8c00",
            "subtitleText" => "#464646",
            "highlight" => "#fb8c00"
        ],
        "dark" => [
            "background" => "#151515",
            "stroke" => "#e4e2e2",
            "titleText" => "#fefefe",
            "currentStreak" => "#fefefe",
            "ring" => "#fb8c00",
            "subtitleText" => "#9e9e9e",
            "highlight" => "#fb8c00"
        ],
        "highcontrast" => [
            "background" => "#000000",
            "stroke" => "#bebebe",
            "titleText" => "#ffffff",
            "currentStreak" => "#ffffff",
            "ring" => "#fb8c00",
            "subtitleText" => "#c5c5c5",
            "highlight" => "#fb8c00"
        ],
        // themes from to https://github.com/anuraghazra/github-readme-stats to maintain consistency among all the stats card
        "radical" => [
            "background" => "#141321",
            "stroke" => "#e4e2e2",
            "titleText" => "#fe428e",
            "ring" => "#fe428e",
            "subtitleText" => "#a9fef7",
            "highlight" => "#f8d847",
            "currentStreak" => "#f8d847"
        ],
        "merko" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#abd200",
            "ring" => "#abd200",
            "highlight" => "#b7d364",
            "currentStreak" => "#b7d364",
            "subtitleText" => "#68b587",
            "background" => "#0a0f0b"
        ],
        "gruvbox" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#fabd2f",
            "ring" => "#fabd2f",
            "highlight" => "#fe8019",
            "currentStreak" => "#fe8019",
            "subtitleText" => "#8ec07c",
            "background" => "#282828"
        ],
        // duo: compatible with both light and dark mode
        "gruvbox_duo" => [
            "stroke" => "#a8a8a8",
            "titleText" => "#fabd2f",
            "ring" => "#fabd2f",
            "highlight" => "#fe8019",
            "currentStreak" => "#fe8019",
            "subtitleText" => "#8ec07c",
            "background" => "#0000", // transparent background
        ],
        "tokyonight" => [
            "background" => "#1a1b27",
            "stroke" => "#e4e2e2",
            "titleText" => "#70a5fd",
            "ring" => "#70a5fd",
            "subtitleText" => "#38bdae",
            "highlight" => "#bf91f3",
            "currentStreak" => "#bf91f3"
        ],
        "tokyonight_duo" => [
            "background" => "#0000",
            "stroke" => "#a8a8a8",
            "titleText" => "#70a5fd",
            "ring" => "#70a5fd",
            "subtitleText" => "#38bdae",
            "highlight" => "#bf91f3",
            "currentStreak" => "#bf91f3"
        ],
        "onedark" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#e4bf7a",
            "ring" => "#e4bf7a",
            "highlight" => "#8eb573",
            "currentStreak" => "#8eb573",
            "subtitleText" => "#df6d74",
            "background" => "#282c34"
        ],
        "onedark_duo" => [
            "stroke" => "#a8a8a8",
            "titleText" => "#e4bf7a",
            "ring" => "#e4bf7a",
            "highlight" => "#8eb573",
            "currentStreak" => "#8eb573",
            "subtitleText" => "#df6d74",
            "background" => "#0000"
        ],
        "cobalt" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#e683d9",
            "ring" => "#e683d9",
            "highlight" => "#0480ef",
            "currentStreak" => "#0480ef",
            "subtitleText" => "#75eeb2",
            "background" => "#0000"
        ],
        "synthwave" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#e2e9ec",
            "ring" => "#e2e9ec",
            "highlight" => "#ef8539",
            "currentStreak" => "#ef8539",
            "subtitleText" => "#e5289e",
            "background" => "#2b213a"
        ],
        "dracula" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#ff6e96",
            "ring" => "#ff6e96",
            "highlight" => "#79dafa",
            "currentStreak" => "#79dafa",
            "subtitleText" => "#f8f8f2",
            "background" => "#282a36"
        ],
        "prussian" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#bddfff",
            "ring" => "#bddfff",
            "highlight" => "#38a0ff",
            "currentStreak" => "#38a0ff",
            "subtitleText" => "#6e93b5",
            "background" => "#172f45"
        ],
        "monokai" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#eb1f6a",
            "ring" => "#eb1f6a",
            "highlight" => "#e28905",
            "currentStreak" => "#e28905",
            "subtitleText" => "#f1f1eb",
            "background" => "#272822"
        ],
        "vue" => [
            "stroke" => "#a8a8a8",
            "titleText" => "#41b883",
            "ring" => "#41b883",
            "highlight" => "#41b883",
            "currentStreak" => "#41b883",
            "subtitleText" => "#273849",
            "background" => "#fffefe"
        ],
        "vue-dark" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#41b883",
            "ring" => "#41b883",
            "highlight" => "#41b883",
            "currentStreak" => "#41b883",
            "subtitleText" => "#fffefe",
            "background" => "#273849"
        ],
        "shades-of-purple" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#fad000",
            "ring" => "#fad000",
            "highlight" => "#b362ff",
            "currentStreak" => "#b362ff",
            "subtitleText" => "#a599e9",
            "background" => "#2d2b55"
        ],
        "nightowl" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#c792ea",
            "ring" => "#c792ea",
            "highlight" => "#ffeb95",
            "currentStreak" => "#ffeb95",
            "subtitleText" => "#7fdbca",
            "background" => "#011627"
        ],
        "buefy" => [
            "stroke" => "#a8a8a8",
            "titleText" => "#7957d5",
            "ring" => "#7957d5",
            "highlight" => "#ff3860",
            "currentStreak" => "#ff3860",
            "subtitleText" => "#363636",
            "background" => "#ffffff"
        ],
        "buefy-dark" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#7957d5",
            "ring" => "#7957d5",
            "highlight" => "#ff3860",
            "currentStreak" => "#ff3860",
            "subtitleText" => "#ababab",
            "background" => "#1a1b27"
        ],
        "blue-green" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#2f97c1",
            "ring" => "#2f97c1",
            "highlight" => "#f5b700",
            "currentStreak" => "#f5b700",
            "subtitleText" => "#0cf574",
            "background" => "#040f0f"
        ],
        "algolia" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#00AEFF",
            "ring" => "#00AEFF",
            "highlight" => "#2DDE98",
            "currentStreak" => "#2DDE98",
            "subtitleText" => "#FFFFFF",
            "background" => "#050F2C"
        ],
        "great-gatsby" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#ffa726",
            "ring" => "#ffa726",
            "highlight" => "#ffb74d",
            "currentStreak" => "#ffb74d",
            "subtitleText" => "#ffd95b",
            "background" => "#000"
        ],
        "darcula" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#BA5F17",
            "ring" => "#BA5F17",
            "highlight" => "#84628F",
            "currentStreak" => "#84628F",
            "subtitleText" => "#BEBEBE",
            "background" => "#242424"
        ],
        "bear" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#e03c8a",
            "ring" => "#e03c8a",
            "highlight" => "#00AEFF",
            "currentStreak" => "#00AEFF",
            "subtitleText" => "#bcb28d",
            "background" => "#1f2023"
        ],
        "solarized-dark" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#268bd2",
            "ring" => "#268bd2",
            "highlight" => "#b58900",
            "currentStreak" => "#b58900",
            "subtitleText" => "#859900",
            "background" => "#002b36"
        ],
        "solarized-light" => [
            "stroke" => "#ababab",
            "titleText" => "#268bd2",
            "ring" => "#268bd2",
            "highlight" => "#b58900",
            "currentStreak" => "#b58900",
            "subtitleText" => "#859900",
            "background" => "#fdf6e3"
        ],
        "chartreuse-dark" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#7fff00",
            "ring" => "#7fff00",
            "highlight" => "#00AEFF",
            "currentStreak" => "#00AEFF",
            "subtitleText" => "#fff",
            "background" => "#000"
        ],
        "nord" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#81a1c1",
            "ring" => "#81a1c1",
            "subtitleText" => "#d8dee9",
            "highlight" => "#88c0d0",
            "currentStreak" => "#88c0d0",
            "background" => "#2e3440"
        ],
        "gotham" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#2aa889",
            "ring" => "#2aa889",
            "highlight" => "#599cab",
            "currentStreak" => "#599cab",
            "subtitleText" => "#99d1ce",
            "background" => "#0c1014"
        ],
        "material-palenight" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#c792ea",
            "ring" => "#c792ea",
            "highlight" => "#89ddff",
            "currentStreak" => "#89ddff",
            "subtitleText" => "#a6accd",
            "background" => "#292d3e"
        ],
        "graywhite" => [
            "stroke" => "#ababab",
            "titleText" => "#24292e",
            "ring" => "#24292e",
            "highlight" => "#24292e",
            "currentStreak" => "#24292e",
            "subtitleText" => "#24292e",
            "background" => "#ffffff"
        ],
        "vision-friendly-dark" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#ffb000",
            "ring" => "#ffb000",
            "highlight" => "#785ef0",
            "currentStreak" => "#785ef0",
            "subtitleText" => "#ffffff",
            "background" => "#000000"
        ],
        "ayu-mirage" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#f4cd7c",
            "ring" => "#f4cd7c",
            "highlight" => "#73d0ff",
            "currentStreak" => "#73d0ff",
            "subtitleText" => "#c7c8c2",
            "background" => "#1f2430"
        ],
        "midnight-purple" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#9745f5",
            "ring" => "#9745f5",
            "highlight" => "#9f4bff",
            "currentStreak" => "#9f4bff",
            "subtitleText" => "#ffffff",
            "background" => "#000000"
        ],
        "calm" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#e07a5f",
            "ring" => "#e07a5f",
            "highlight" => "#edae49",
            "currentStreak" => "#edae49",
            "subtitleText" => "#ebcfb2",
            "background" => "#373f51"
        ],
        "flag-india" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#ff8f1c",
            "ring" => "#ff8f1c",
            "highlight" => "#250E62",
            "currentStreak" => "#250E62",
            "subtitleText" => "#509E2F",
            "background" => "#ffffff"
        ],
        "omni" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#FF79C6",
            "ring" => "#FF79C6",
            "highlight" => "#e7de79",
            "currentStreak" => "#e7de79",
            "subtitleText" => "#E1E1E6",
            "background" => "#191622"
        ],
        "react" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#61dafb",
            "ring" => "#61dafb",
            "highlight" => "#61dafb",
            "currentStreak" => "#61dafb",
            "subtitleText" => "#ffffff",
            "background" => "#20232a"
        ],
        "jolly" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#ff64da",
            "ring" => "#ff64da",
            "highlight" => "#a960ff",
            "currentStreak" => "#a960ff",
            "subtitleText" => "#ffffff",
            "background" => "#291B3E"
        ],
        "maroongold" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#F7EF8A",
            "ring" => "#F7EF8A",
            "highlight" => "#F7EF8A",
            "currentStreak" => "#F7EF8A",
            "subtitleText" => "#E0AA3E",
            "background" => "#260000"
        ],
        "yeblu" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#ffff00",
            "ring" => "#ffff00",
            "highlight" => "#ffff00",
            "currentStreak" => "#ffff00",
            "subtitleText" => "#ffffff",
            "background" => "#002046"
        ],
        "blueberry" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#82aaff",
            "ring" => "#82aaff",
            "highlight" => "#89ddff",
            "currentStreak" => "#89ddff",
            "subtitleText" => "#27e8a7",
            "background" => "#242938"
        ],
        "blueberry_duo" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#82aaff",
            "ring" => "#82aaff",
            "highlight" => "#89ddff",
            "currentStreak" => "#89ddff",
            "subtitleText" => "#27e8a7",
            "background" => "#0000"
        ],
        "slateorange" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#faa627",
            "ring" => "#faa627",
            "highlight" => "#faa627",
            "currentStreak" => "#faa627",
            "subtitleText" => "#ffffff",
            "background" => "#36393f"
        ],
        "kacho_ga" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#bf4a3f",
            "ring" => "#bf4a3f",
            "highlight" => "#a64833",
            "currentStreak" => "#a64833",
            "subtitleText" => "#d9c8a9",
            "background" => "#402b23"
        ],
        "ads-juicy-fresh" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#fff",
            "ring" => "#81ff00",
            "highlight" => "#ff5700",
            "currentStreak" => "#fff",
            "subtitleText" => "#6562af",
            "background" => "#0d0c15"
        ],
        "black-ice" => [
            "stroke" => "#e4e2e2",
            "titleText" => "#fff",
            "ring" => "#00e7ff",
            "highlight" => "#00e7ff",
            "currentStreak" => "#fff",
            "subtitleText" => "#9f9f9f",
            "background" => "#151515"
        ]
    );

    // if offset is valid
    if (array_key_exists($theme, $themes)) {
        return $themes[$theme];
    }

    // default theme
    return $themes["default"];
}
