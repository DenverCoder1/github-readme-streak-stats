<?php declare (strict_types = 1);

/**
 * Convert date from Y-M-D to more human-readable format
 *
 * @param string $dateString String in Y-M-D format
 * @return string formatted Date string
 */
function formatDate(string $dateString): string
{
    $date = new DateTime($dateString);
    // if current year, display only month and day
    if (date_format($date, "Y") == date("Y")) {
        return date_format($date, "M j");
    }
    // otherwise, display month, day, and year
    return date_format($date, "M j, Y");
}

/**
 * Check theme and color customization parameters to generate a theme mapping
 *
 * @param array<string, string> $params Request parameters
 * @return array<string, string> The chosen theme or default
 */
function getRequestedTheme(array $params): array
{
    /**
     * @var array<string, array<string, string>> $THEMES
     * List of theme names mapped to labeled colors
     */
    $THEMES = include "themes.php";

    /**
     * @var array<string> $CSS_COLORS
     * List of valid CSS colors
     */
    $CSS_COLORS = include "colors.php";

    // get theme colors
    if (isset($params["theme"]) && array_key_exists($params["theme"], $THEMES)) {
        $theme = $THEMES[$params["theme"]];
    }
    // no theme specified, get default
    else {
        $theme = $THEMES["default"];
    }

    // personal theme customizations
    $properties = array_keys($theme);
    foreach ($properties as $prop) {
        // check if each property was passed as a parameter
        if (isset($params[$prop])) {
            // ignore case
            $param = strtolower($params[$prop]);
            // check if color is valid hex color (3, 4, 6, or 8 hex digits)
            if (preg_match("/^([a-f0-9]{3}|[a-f0-9]{4}|[a-f0-9]{6}|[a-f0-9]{8})$/", $param)) {
                // set property
                $theme[$prop] = "#" . $param;
            }
            // check if color is valid css color
            else if (in_array($param, $CSS_COLORS)) {
                // set property
                $theme[$prop] = $param;
            }
        }
    }

    // hide borders
    if (isset($params["hide_border"]) && $params["hide_border"] == "true") {
        $theme["border"] = "#0000"; // transparent
    }

    return $theme;
}

/**
 * Generate SVG output for a stats array
 *
 * @param array<string, mixed> $stats Streak stats
 * @param array<string, string>|NULL $params Request parameters
 *
 * @return string The generated SVG Streak Stats card
 */
function generateCard(array $stats, array $params = null): string
{
    // get requested theme, use $_REQUEST if no params array specified
    $theme = getRequestedTheme($params ?? $_REQUEST);

    // total contributions
    $totalContributions = $stats["totalContributions"];
    $firstContribution = formatDate($stats["firstContribution"]);
    $totalContributionsRange = $firstContribution . " - Present";

    // current streak
    $currentStreak = $stats["currentStreak"]["length"];
    $currentStreakStart = formatDate($stats["currentStreak"]["start"]);
    $currentStreakEnd = formatDate($stats["currentStreak"]["end"]);
    $currentStreakRange = $currentStreakStart;
    if ($currentStreakStart != $currentStreakEnd) {
        $currentStreakRange .= " - " . $currentStreakEnd;
    }

    // longest streak
    $longestStreak = $stats["longestStreak"]["length"];
    $longestStreakStart = formatDate($stats["longestStreak"]["start"]);
    $longestStreakEnd = formatDate($stats["longestStreak"]["end"]);
    $longestStreakRange = $longestStreakStart;
    if ($longestStreakStart != $longestStreakEnd) {
        $longestStreakRange .= " - " . $longestStreakEnd;
    }

    return "
    <svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' style='isolation:isolate' viewBox='0 0 495 195' width='495px' height='195px'>
        <style>
            @import url(https://fonts.googleapis.com/css?family=Open+Sans:400,700);
            @keyframes currstreak {
                0% { font-size: 3px; opacity: 0.2; }
                80% { font-size: 34px; opacity: 1; }
                100% { font-size: 28px; opacity: 1; }
            }
            @keyframes fadein {
                0% { opacity: 0; }
                100% { opacity: 1; }
            }
        </style>
        <defs>
            <clipPath id='_clipPath_OZGVUqgkTHHpPTYeqOmK3uLgktRVSwWw'>
                <rect width='495' height='195'/>
            </clipPath>
        </defs>
        <g clip-path='url(#_clipPath_OZGVUqgkTHHpPTYeqOmK3uLgktRVSwWw)'>
            <g style='isolation:isolate'>
                <path d='M 4.5 0 L 490.5 0 C 492.984 0 495 2.016 495 4.5 L 495 190.5 C 495 192.984 492.984 195 490.5 195 L 4.5 195 C 2.016 195 0 192.984 0 190.5 L 0 4.5 C 0 2.016 2.016 0 4.5 0 Z'
                    style='stroke: {$theme["border"]}; fill: {$theme["background"]};stroke-miterlimit:10;rx: 4.5;'/>
            </g>
            <g style='isolation:isolate'>
                <line x1='330' y1='28' x2='330' y2='170' vector-effect='non-scaling-stroke' stroke-width='1' stroke='{$theme["stroke"]}' stroke-linejoin='miter' stroke-linecap='square' stroke-miterlimit='3'/>
                <line x1='165' y1='28' x2='165' y2='170' vector-effect='non-scaling-stroke' stroke-width='1' stroke='{$theme["stroke"]}' stroke-linejoin='miter' stroke-linecap='square' stroke-miterlimit='3'/>
            </g>
            <g style='isolation:isolate'>
                <!-- Total Contributions Big Number -->
                <g transform='translate(1,48)'>
                    <rect width='163' height='50' stroke='none' fill='none'></rect>
                    <text x='81.5' y='25' dy='0.25em' stroke-width='0' text-anchor='middle' style='font-family:&quot;Open Sans&quot;, Roboto, system-ui, sans-serif;font-weight:700;font-size:28px;font-style:normal;fill:{$theme["sideNums"]};stroke:none; opacity: 0; animation: fadein 0.5s linear forwards 0.6s;'>
                        {$totalContributions}
                    </text>
                </g>

                <!-- Total Contributions Label -->
                <g transform='translate(1,84)'>
                    <rect width='163' height='50' stroke='none' fill='none'></rect>
                    <text x='81.5' y='25' dy='0.25em' stroke-width='0' text-anchor='middle' style='font-family:&quot;Open Sans&quot;, Roboto, system-ui, sans-serif;font-weight:400;font-size:14px;font-style:normal;fill:{$theme["sideLabels"]};stroke:none; opacity: 0; animation: fadein 0.5s linear forwards 0.7s;'>
                        Total Contributions
                    </text>
                </g>

                <!-- total contributions range -->
                <g transform='translate(1,114)'>
                    <rect width='163' height='50' stroke='none' fill='none'></rect>
                    <text x='81.5' y='25' dy='0.25em' stroke-width='0' text-anchor='middle' style='font-family:&quot;Open Sans&quot;, Roboto, system-ui, sans-serif;font-weight:400;font-size:12px;font-style:normal;fill:{$theme["dates"]};stroke:none; opacity: 0; animation: fadein 0.5s linear forwards 0.8s;'>
                        {$totalContributionsRange}
                    </text>
                </g>
            </g>
            <g style='isolation:isolate'>
                <!-- Current Streak Big Number -->
                <g transform='translate(166,48)'>
                    <rect width='163' height='50' stroke='none' fill='none'></rect>
                    <text x='81.5' y='25' dy='0.25em' stroke-width='0' text-anchor='middle' style='font-family:&quot;Open Sans&quot;, Roboto, system-ui, sans-serif;font-weight:700;font-size:28px;font-style:normal;fill:{$theme["currStreakNum"]};stroke:none;animation: currstreak 0.6s linear forwards;'>
                        {$currentStreak}
                    </text>
                </g>

                <!-- Current Streak Label -->
                <g transform='translate(166,108)'>
                    <rect width='163' height='50' stroke='none' fill='none'></rect>
                    <text x='81.5' y='25' dy='0.25em' stroke-width='0' text-anchor='middle' style='font-family:&quot;Open Sans&quot;, Roboto, system-ui, sans-serif;font-weight:700;font-size:14px;font-style:normal;fill:{$theme["currStreakLabel"]};stroke:none;opacity: 0; animation: fadein 0.5s linear forwards 0.9s;'>
                        Current Streak
                    </text>
                </g>

                <!-- Current Streak Range -->
                <g transform='translate(166,145)'>
                    <rect width='163' height='26' stroke='none' fill='none'></rect>
                    <text x='81.5' y='13' dy='0.25em' stroke-width='0' text-anchor='middle' style='font-family:&quot;Open Sans&quot;, Roboto, system-ui, sans-serif;font-weight:400;font-size:12px;font-style:normal;fill:{$theme["dates"]};stroke:none;opacity: 0; animation: fadein 0.5s linear forwards 0.9s;'>
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
                <circle cx='247.5' cy='71' r='40' mask='url(#cut-off-area)' style='fill:none;stroke:{$theme["ring"]};stroke-width:5;opacity: 0; animation: fadein 0.5s linear forwards 0.4s;'></circle>
                <!-- fire icon -->
                <g style='opacity: 0; animation: fadein 0.5s linear forwards 0.6s;'>
                    <path d=' M 235.5 19.5 L 259.5 19.5 L 259.5 43.5 L 235.5 43.5 L 235.5 19.5 Z ' fill='none'/>
                    <path d=' M 249 20.17 C 249 20.17 249.74 22.82 249.74 24.97 C 249.74 27.03 248.39 28.7 246.33 28.7 C 244.26 28.7 242.7 27.03 242.7 24.97 L 242.73 24.61 C 240.71 27.01 239.5 30.12 239.5 33.5 C 239.5 37.92 243.08 41.5 247.5 41.5 C 251.92 41.5 255.5 37.92 255.5 33.5 C 255.5 28.11 252.91 23.3 249 20.17 Z  M 247.21 38.5 C 245.43 38.5 243.99 37.1 243.99 35.36 C 243.99 33.74 245.04 32.6 246.8 32.24 C 248.57 31.88 250.4 31.03 251.42 29.66 C 251.81 30.95 252.01 32.31 252.01 33.7 C 252.01 36.35 249.86 38.5 247.21 38.5 Z ' fill='{$theme["fire"]}'/>
                </g>
            </g>
            <g style='isolation:isolate'>
                <!-- Longest Streak Big Number -->
                <g transform='translate(331,48)'>
                    <rect width='163' height='50' stroke='none' fill='none'></rect>
                    <text x='81.5' y='25' dy='0.25em' stroke-width='0' text-anchor='middle' style='font-family:&quot;Open Sans&quot;, Roboto, system-ui, sans-serif;font-weight:700;font-size:28px;font-style:normal;fill:{$theme["sideNums"]};stroke:none; opacity: 0; animation: fadein 0.5s linear forwards 1.2s;'>
                        {$longestStreak}
                    </text>
                </g>

                <!-- Longest Streak Label -->
                <g transform='translate(331,84)'>
                    <rect width='163' height='50' stroke='none' fill='none'></rect>
                    <text x='81.5' y='25' dy='0.25em' stroke-width='0' text-anchor='middle' style='font-family:&quot;Open Sans&quot;, Roboto, system-ui, sans-serif;font-weight:400;font-size:14px;font-style:normal;fill:{$theme["sideLabels"]};stroke:none;opacity: 0; animation: fadein 0.5s linear forwards 1.3s;'>
                        Longest Streak
                    </text>
                </g>

                <!-- Longest Streak Range -->
                <g transform='translate(331,114)'>
                    <rect width='163' height='50' stroke='none' fill='none'></rect>
                    <text x='81.5' y='25' dy='0.25em' stroke-width='0' text-anchor='middle' style='font-family:&quot;Open Sans&quot;, Roboto, system-ui, sans-serif;font-weight:400;font-size:12px;font-style:normal;fill:{$theme["dates"]};stroke:none;opacity: 0; animation: fadein 0.5s linear forwards 1.4s;'>
                        {$longestStreakRange}
                    </text>
                </g>
            </g>
        </g>
    </svg>
    ";
}

/**
 * Generate SVG displaying an error message
 *
 * @param string $message The error message to display
 * @param array<string, string>|NULL $params Request parameters
 *
 * @return string The generated SVG error card
 */
function generateErrorCard(string $message, array $params = null): string
{
    // get requested theme, use $_REQUEST if no params array specified
    $theme = getRequestedTheme($params ?? $_REQUEST);

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
                    style='stroke: {$theme["border"]}; fill: {$theme["background"]};stroke-miterlimit:10;rx: 4.5;'/>
            </g>
            <g style='isolation:isolate'>
                <!-- Error Label -->
                <g transform='translate(166,108)'>
                    <rect width='163' height='50' stroke='none' fill='none'></rect>
                    <text x='81.5' y='50' dy='0.25em' stroke-width='0' text-anchor='middle' style='font-family:&quot;Open Sans&quot;, Roboto, system-ui, sans-serif;font-weight:400;font-size:14px;font-style:normal;fill:{$theme["sideLabels"]};stroke:none;'>
                        {$message}
                    </text>
                </g>

                <!-- mask for background behind face -->
                <defs>
                    <mask id='cut-off-area'>
                    <rect x='0' y='0' width='500' height='500' fill='white' />
                    <ellipse cx='247.5' cy='31' rx='13' ry='18'/>
                    </mask>
                </defs>
                <!-- Sad face -->
                <g>
                    <path style='fill:{$theme["fire"]};' d='M248,35.8c-25.2,0-45.7,20.5-45.7,45.7s20.5,45.8,45.7,45.8s45.7-20.5,45.7-45.7S273.2,35.8,248,35.8z M248,122.3c-11.2,0-21.4-4.5-28.8-11.9c-2.9-2.9-5.4-6.3-7.4-10c-3-5.7-4.6-12.1-4.6-18.9c0-22.5,18.3-40.8,40.8-40.8 c10.7,0,20.4,4.1,27.7,10.9c3.8,3.5,6.9,7.7,9.1,12.4c2.6,5.3,4,11.3,4,17.6C288.8,104.1,270.5,122.3,248,122.3z'/>
                    <path style='fill:{$theme["fire"]};' d='M252.8,93.8c5.4,1.1,10.3,4.2,13.7,8.6l3.9-3c-4.1-5.3-10-9-16.6-10.4c-10.6-2.2-21.7,1.9-28.3,10.4l3.9,3 C234.9,95.3,244.1,91.9,252.8,93.8z'/>
                    <circle style='fill:{$theme["fire"]};' cx='232.8' cy='71.3' r='4.9'/>
                    <circle style='fill:{$theme["fire"]};' cx='263.4' cy='71.3' r='4.9'/>
                </g>
            </g>
        </g>
    </svg>
    ";
}
