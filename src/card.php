<?php

require_once 'themes.php';

function format($date): string
{
    $date = new DateTime($date);
    // if current year, display only month and day
    if (date_format($date, "Y") == date("Y")) {
        return date_format($date, "M j");
    }
    // otherwise, display month, day, and year
    return date_format($date, "M j, Y");
}

function generateCard($stats): string
{
    // get theme colors
    if (isset($_REQUEST["theme"])) {
        $theme = getTheme($_REQUEST["theme"]);
    }
    // no theme specified
    else {
        $theme = getTheme("default");
    }

    // hide borders
    if (isset($_REQUEST["hide_border"]) && $_REQUEST["hide_border"] == "true") {
        $borderColor = "#0000"; // transparent
    }
    // hide borders is not set or not set to true
    else {
        $borderColor = $theme["border"];
    }

    // total contributions
    $totalContributions = $stats['totalContributions'];
    $firstContribution = format($stats['firstContribution']);
    $totalContributionsRange = $firstContribution . " - Present";

    // current streak
    $currentStreak = $stats['currentStreak']["length"];
    $currentStreakStart = format($stats['currentStreak']["start"]);
    $currentStreakEnd = format($stats['currentStreak']["end"]);
    $currentStreakRange = $currentStreakStart;
    if ($currentStreakStart != $currentStreakEnd) {
        $currentStreakRange .= " - " . $currentStreakEnd;
    }

    // longest streak
    $longestStreak = $stats['longestStreak']["length"];
    $longestStreakStart = format($stats['longestStreak']["start"]);
    $longestStreakEnd = format($stats['longestStreak']["end"]);
    $longestStreakRange = $longestStreakStart;
    if ($longestStreakStart != $longestStreakEnd) {
        $longestStreakRange .= " - " . $longestStreakEnd;
    }

    // TODO: Add animations on load
    return "
    <svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' style='isolation:isolate' viewBox='0 0 495 195' width='495px' height='195px'>
        <style>
            @import url(https://fonts.googleapis.com/css?family=Open+Sans:400,700);
        </style>
        <defs>
            <clipPath id='_clipPath_OZGVUqgkTHHpPTYeqOmK3uLgktRVSwWw'>
                <rect width='495' height='195'/>
            </clipPath>
        </defs>
        <g clip-path='url(#_clipPath_OZGVUqgkTHHpPTYeqOmK3uLgktRVSwWw)'>
            <g style='isolation:isolate'>
                <path d='M 4.5 0 L 490.5 0 C 492.984 0 495 2.016 495 4.5 L 495 190.5 C 495 192.984 492.984 195 490.5 195 L 4.5 195 C 2.016 195 0 192.984 0 190.5 L 0 4.5 C 0 2.016 2.016 0 4.5 0 Z'
                    style='stroke: {$borderColor}; fill: {$theme["background"]};stroke-miterlimit:10;rx: 4.5;'/>
            </g>
            <g style='isolation:isolate'>
                <line x1='330' y1='28' x2='330' y2='170' vector-effect='non-scaling-stroke' stroke-width='1' stroke='{$theme["stroke"]}' stroke-linejoin='miter' stroke-linecap='square' stroke-miterlimit='3'/>
                <line x1='165' y1='28' x2='165' y2='170' vector-effect='non-scaling-stroke' stroke-width='1' stroke='{$theme["stroke"]}' stroke-linejoin='miter' stroke-linecap='square' stroke-miterlimit='3'/>
            </g>
            <g style='isolation:isolate'>
                <!-- Total Contributions Big Number -->
                <g transform='translate(1,48)'>
                    <rect width='163' height='50' stroke='none' fill='none'></rect>
                    <text x='81.5' y='25' dominant-baseline='middle' stroke-width='0' text-anchor='middle' style='font-family:&quot;Open Sans&quot;, Roboto, system-ui, sans-serif;font-weight:700;font-size:28px;font-style:normal;fill:{$theme["sideNums"]};stroke:none;'>
                        {$totalContributions}
                    </text>
                </g>

                <!-- Total Contributions Label -->
                <g transform='translate(1,84)'>
                    <rect width='163' height='50' stroke='none' fill='none'></rect>
                    <text x='81.5' y='25' dominant-baseline='middle' stroke-width='0' text-anchor='middle' style='font-family:&quot;Open Sans&quot;, Roboto, system-ui, sans-serif;font-weight:400;font-size:14px;font-style:normal;fill:{$theme["sideLabels"]};stroke:none;'>
                        Total Contributions
                    </text>
                </g>

                <!-- total contributions range -->
                <g transform='translate(1,114)'>
                    <rect width='163' height='50' stroke='none' fill='none'></rect>
                    <text x='81.5' y='25' dominant-baseline='middle' stroke-width='0' text-anchor='middle' style='font-family:&quot;Open Sans&quot;, Roboto, system-ui, sans-serif;font-weight:400;font-size:12px;font-style:normal;fill:{$theme["dates"]};stroke:none;'>
                        {$totalContributionsRange}
                    </text>
                </g>
            </g>
            <g style='isolation:isolate'>
                <!-- Current Streak Big Number -->
                <g transform='translate(166,48)'>
                    <rect width='163' height='50' stroke='none' fill='none'></rect>
                    <text x='81.5' y='25' dominant-baseline='middle' stroke-width='0' text-anchor='middle' style='font-family:&quot;Open Sans&quot;, Roboto, system-ui, sans-serif;font-weight:700;font-size:28px;font-style:normal;fill:{$theme["currStreakNum"]};stroke:none;'>
                        {$currentStreak}
                    </text>
                </g>

                <!-- Current Streak Label -->
                <g transform='translate(166,108)'>
                    <rect width='163' height='50' stroke='none' fill='none'></rect>
                    <text x='81.5' y='25' dominant-baseline='middle' stroke-width='0' text-anchor='middle' style='font-family:&quot;Open Sans&quot;, Roboto, system-ui, sans-serif;font-weight:700;font-size:14px;font-style:normal;fill:{$theme["currStreakLabel"]};stroke:none;'>
                        Current Streak
                    </text>
                </g>

                <!-- Current Streak Range -->
                <g transform='translate(166,145)'>
                    <rect width='163' height='26' stroke='none' fill='none'></rect>
                    <text x='81.5' y='13' dominant-baseline='middle' stroke-width='0' text-anchor='middle' style='font-family:&quot;Open Sans&quot;, Roboto, system-ui, sans-serif;font-weight:400;font-size:12px;font-style:normal;fill:{$theme["dates"]};stroke:none;'>
                        {$currentStreakRange}
                    </text>
                </g>

                <!-- mask for background behind fire -->
                <defs>
                    <mask id='cut-off-area'>
                    <rect x='0' y='0' width='500' height='500' fill='white' />
                    <ellipse cx='247.5' cy='31' rx='13' ry='18'/>
                    </mask>
                </defs>
                <!-- ring around number -->
                <circle cx='247.5' cy='71' r='40' mask='url(#cut-off-area)' style='fill:none;stroke:{$theme["ring"]};stroke-width:5;'></circle>
                <!-- fire icon -->
                <g>
                    <path d=' M 235.5 19.5 L 259.5 19.5 L 259.5 43.5 L 235.5 43.5 L 235.5 19.5 Z ' fill='none'/>
                    <path d=' M 249 20.17 C 249 20.17 249.74 22.82 249.74 24.97 C 249.74 27.03 248.39 28.7 246.33 28.7 C 244.26 28.7 242.7 27.03 242.7 24.97 L 242.73 24.61 C 240.71 27.01 239.5 30.12 239.5 33.5 C 239.5 37.92 243.08 41.5 247.5 41.5 C 251.92 41.5 255.5 37.92 255.5 33.5 C 255.5 28.11 252.91 23.3 249 20.17 Z  M 247.21 38.5 C 245.43 38.5 243.99 37.1 243.99 35.36 C 243.99 33.74 245.04 32.6 246.8 32.24 C 248.57 31.88 250.4 31.03 251.42 29.66 C 251.81 30.95 252.01 32.31 252.01 33.7 C 252.01 36.35 249.86 38.5 247.21 38.5 Z ' fill='{$theme["fire"]}'/>
                </g>
            </g>
            <g style='isolation:isolate'>
                <!-- Longest Streak Big Number -->
                <g transform='translate(331,48)'>
                    <rect width='163' height='50' stroke='none' fill='none'></rect>
                    <text x='81.5' y='25' dominant-baseline='middle' stroke-width='0' text-anchor='middle' style='font-family:&quot;Open Sans&quot;, Roboto, system-ui, sans-serif;font-weight:700;font-size:28px;font-style:normal;fill:{$theme["sideNums"]};stroke:none;'>
                        {$longestStreak}
                    </text>
                </g>

                <!-- Longest Streak Label -->
                <g transform='translate(331,84)'>
                    <rect width='163' height='50' stroke='none' fill='none'></rect>
                    <text x='81.5' y='25' dominant-baseline='middle' stroke-width='0' text-anchor='middle' style='font-family:&quot;Open Sans&quot;, Roboto, system-ui, sans-serif;font-weight:400;font-size:14px;font-style:normal;fill:{$theme["sideLabels"]};stroke:none;'>
                        Longest Streak
                    </text>
                </g>

                <!-- Longest Streak Range -->
                <g transform='translate(331,114)'>
                    <rect width='163' height='50' stroke='none' fill='none'></rect>
                    <text x='81.5' y='25' dominant-baseline='middle' stroke-width='0' text-anchor='middle' style='font-family:&quot;Open Sans&quot;, Roboto, system-ui, sans-serif;font-weight:400;font-size:12px;font-style:normal;fill:{$theme["dates"]};stroke:none;'>
                        {$longestStreakRange}
                    </text>
                </g>
            </g>
        </g>
    </svg>
  ";
}
