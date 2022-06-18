<?php

declare(strict_types=1);

/**
 * Convert date from Y-M-D to more human-readable format
 *
 * @param string $dateString String in Y-M-D format
 * @param string|null $format Date format to use, or null to use locale default
 * @param string $locale Locale code
 * @return string Formatted Date string
 */
function formatDate(string $dateString, string|null $format, string $locale): string
{
    $date = new DateTime($dateString);
    $formatted = "";
    $patternGenerator = new IntlDatePatternGenerator($locale);
    // if current year, display only month and day
    if (date_format($date, "Y") == date("Y")) {
        if ($format) {
            // remove brackets and all text within them
            $formatted = date_format($date, preg_replace("/\[.*?\]/", "", $format));
        } else {
            // format without year using locale
            $pattern = $patternGenerator->getBestPattern("MMM d");
            $dateFormatter = new IntlDateFormatter(
                $locale,
                IntlDateFormatter::MEDIUM,
                IntlDateFormatter::NONE,
                pattern: $pattern
            );
            $formatted = $dateFormatter->format($date);
        }
    }
    // otherwise, display month, day, and year
    else {
        if ($format) {
            // remove brackets, but leave text within them
            $formatted = date_format($date, str_replace(["[", "]"], "", $format));
        } else {
            // format with year using locale
            $pattern = $patternGenerator->getBestPattern("YYYY MMM d");
            $dateFormatter = new IntlDateFormatter(
                $locale,
                IntlDateFormatter::MEDIUM,
                IntlDateFormatter::NONE,
                pattern: $pattern
            );
            $formatted = $dateFormatter->format($date);
        }
    }
    // sanitize and return formatted date
    return htmlspecialchars($formatted);
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
            elseif (in_array($param, $CSS_COLORS)) {
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
 *
 * @throws InvalidArgumentException If a locale does not exist
 */
function generateCard(array $stats, array $params = null): string
{
    $params = $params ?? $_REQUEST;

    // get requested theme
    $theme = getRequestedTheme($params);

    // get the labels from the translations file
    $translations = include "translations.php";
    // get requested locale, default to English
    $localeCode = $params["locale"] ?? "en";
    $localeTranslations = $translations[$localeCode] ?? [];
    // add missing translations from English
    $localeTranslations += $translations["en"];

    // get date format
    // locale date formatter (used only if date_format is not specified)
    $dateFormat = $params["date_format"] ?? ($localeTranslations["date_format"] ?? null);

    // number formatter
    $numFormatter = new NumberFormatter($localeCode, NumberFormatter::DECIMAL);

    // total contributions
    $totalContributions = $numFormatter->format($stats["totalContributions"]);
    $firstContribution = formatDate($stats["firstContribution"], $dateFormat, $localeCode);
    $totalContributionsRange = $firstContribution . " - " . $localeTranslations["Present"];

    // current streak
    $currentStreak = $numFormatter->format($stats["currentStreak"]["length"]);
    $currentStreakStart = formatDate($stats["currentStreak"]["start"], $dateFormat, $localeCode);
    $currentStreakEnd = formatDate($stats["currentStreak"]["end"], $dateFormat, $localeCode);
    $currentStreakRange = $currentStreakStart;
    if ($currentStreakStart != $currentStreakEnd) {
        $currentStreakRange .= " - " . $currentStreakEnd;
    }

    // longest streak
    $longestStreak = $numFormatter->format($stats["longestStreak"]["length"]);
    $longestStreakStart = formatDate($stats["longestStreak"]["start"], $dateFormat, $localeCode);
    $longestStreakEnd = formatDate($stats["longestStreak"]["end"], $dateFormat, $localeCode);
    $longestStreakRange = $longestStreakStart;
    if ($longestStreakStart != $longestStreakEnd) {
        $longestStreakRange .= " - " . $longestStreakEnd;
    }

    return "<svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' style='isolation:isolate' viewBox='0 0 495 195' width='495px' height='195px'>
        <style>
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
            <clipPath id='outer_rectangle'>
                <rect width='495' height='195'/>
            </clipPath>
            <mask id='mask_out_ring_behind_fire'>
                <rect width='495' height='195' fill='white'/>
                <ellipse id='mask-ellipse' cx='247.5' cy='32' rx='13' ry='18' fill='black'/>
            </mask>
        </defs>
        <g clip-path='url(#outer_rectangle)'>
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
                    <text x='81.5' y='32' stroke-width='0' text-anchor='middle' style='font-family:Segoe UI, Ubuntu, sans-serif;font-weight:700;font-size:28px;font-style:normal;fill:{$theme["sideNums"]};stroke:none; opacity: 0; animation: fadein 0.5s linear forwards 0.6s;'>
                        {$totalContributions}
                    </text>
                </g>

                <!-- Total Contributions Label -->
                <g transform='translate(1,84)'>
                    <rect width='163' height='50' stroke='none' fill='none'></rect>
                    <text x='81.5' y='32' stroke-width='0' text-anchor='middle' style='font-family:Segoe UI, Ubuntu, sans-serif;font-weight:400;font-size:14px;font-style:normal;fill:{$theme["sideLabels"]};stroke:none; opacity: 0; animation: fadein 0.5s linear forwards 0.7s;'>
                        {$localeTranslations["Total Contributions"]}
                    </text>
                </g>

                <!-- total contributions range -->
                <g transform='translate(1,114)'>
                    <rect width='163' height='50' stroke='none' fill='none'></rect>
                    <text x='81.5' y='32' stroke-width='0' text-anchor='middle' style='font-family:Segoe UI, Ubuntu, sans-serif;font-weight:400;font-size:12px;font-style:normal;fill:{$theme["dates"]};stroke:none; opacity: 0; animation: fadein 0.5s linear forwards 0.8s;'>
                        {$totalContributionsRange}
                    </text>
                </g>
            </g>
            <g style='isolation:isolate'>
                <!-- Current Streak Big Number -->
                <g transform='translate(166,48)'>
                    <rect width='163' height='50' stroke='none' fill='none'></rect>
                    <text x='81.5' y='32' stroke-width='0' text-anchor='middle' style='font-family:Segoe UI, Ubuntu, sans-serif;font-weight:700;font-size:28px;font-style:normal;fill:{$theme["currStreakNum"]};stroke:none;animation: currstreak 0.6s linear forwards;'>
                        {$currentStreak}
                    </text>
                </g>

                <!-- Current Streak Label -->
                <g transform='translate(166,108)'>
                    <rect width='163' height='50' stroke='none' fill='none'></rect>
                    <text x='81.5' y='32' stroke-width='0' text-anchor='middle' style='font-family:Segoe UI, Ubuntu, sans-serif;font-weight:700;font-size:14px;font-style:normal;fill:{$theme["currStreakLabel"]};stroke:none;opacity: 0; animation: fadein 0.5s linear forwards 0.9s;'>
                        {$localeTranslations["Current Streak"]}
                    </text>
                </g>

                <!-- Current Streak Range -->
                <g transform='translate(166,145)'>
                    <rect width='163' height='26' stroke='none' fill='none'></rect>
                    <text x='81.5' y='21' stroke-width='0' text-anchor='middle' style='font-family:Segoe UI, Ubuntu, sans-serif;font-weight:400;font-size:12px;font-style:normal;fill:{$theme["dates"]};stroke:none;opacity: 0; animation: fadein 0.5s linear forwards 0.9s;'>
                        {$currentStreakRange}
                    </text>
                </g>

                <!-- ring around number -->
                <g mask='url(#mask_out_ring_behind_fire)'>
                    <circle cx='247.5' cy='71' r='40' style='fill:none;stroke:{$theme["ring"]};stroke-width:5;opacity: 0; animation: fadein 0.5s linear forwards 0.4s;'></circle>
                </g>
                <!-- fire icon -->
                <g style='opacity: 0; animation: fadein 0.5s linear forwards 0.6s; stroke-opacity: 0;'>
                    <path d=' M 235.5 19.5 L 259.5 19.5 L 259.5 43.5 L 235.5 43.5 L 235.5 19.5 Z ' fill='none' stroke-opacity='0'/>
                    <path d=' M 249 20.17 C 249 20.17 249.74 22.82 249.74 24.97 C 249.74 27.03 248.39 28.7 246.33 28.7 C 244.26 28.7 242.7 27.03 242.7 24.97 L 242.73 24.61 C 240.71 27.01 239.5 30.12 239.5 33.5 C 239.5 37.92 243.08 41.5 247.5 41.5 C 251.92 41.5 255.5 37.92 255.5 33.5 C 255.5 28.11 252.91 23.3 249 20.17 Z  M 247.21 38.5 C 245.43 38.5 243.99 37.1 243.99 35.36 C 243.99 33.74 245.04 32.6 246.8 32.24 C 248.57 31.88 250.4 31.03 251.42 29.66 C 251.81 30.95 252.01 32.31 252.01 33.7 C 252.01 36.35 249.86 38.5 247.21 38.5 Z ' fill='{$theme["fire"]}' stroke-opacity='0'/>
                </g>

            </g>
            <g style='isolation:isolate'>
                <!-- Longest Streak Big Number -->
                <g transform='translate(331,48)'>
                    <rect width='163' height='50' stroke='none' fill='none'></rect>
                    <text x='81.5' y='32' stroke-width='0' text-anchor='middle' style='font-family:Segoe UI, Ubuntu, sans-serif;font-weight:700;font-size:28px;font-style:normal;fill:{$theme["sideNums"]};stroke:none; opacity: 0; animation: fadein 0.5s linear forwards 1.2s;'>
                        {$longestStreak}
                    </text>
                </g>

                <!-- Longest Streak Label -->
                <g transform='translate(331,84)'>
                    <rect width='163' height='50' stroke='none' fill='none'></rect>
                    <text x='81.5' y='32' stroke-width='0' text-anchor='middle' style='font-family:Segoe UI, Ubuntu, sans-serif;font-weight:400;font-size:14px;font-style:normal;fill:{$theme["sideLabels"]};stroke:none;opacity: 0; animation: fadein 0.5s linear forwards 1.3s;'>
                        {$localeTranslations["Longest Streak"]}
                    </text>
                </g>

                <!-- Longest Streak Range -->
                <g transform='translate(331,114)'>
                    <rect width='163' height='50' stroke='none' fill='none'></rect>
                    <text x='81.5' y='32' stroke-width='0' text-anchor='middle' style='font-family:Segoe UI, Ubuntu, sans-serif;font-weight:400;font-size:12px;font-style:normal;fill:{$theme["dates"]};stroke:none;opacity: 0; animation: fadein 0.5s linear forwards 1.4s;'>
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
    $params = $params ?? $_REQUEST;

    // get requested theme, use $_REQUEST if no params array specified
    $theme = getRequestedTheme($params);

    return "<svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' style='isolation:isolate' viewBox='0 0 495 195' width='495px' height='195px'>
        <style>
            a {
                fill: {$theme["dates"]};
            }
        </style>
        <defs>
            <clipPath id='outer_rectangle'>
                <rect width='495' height='195'/>
            </clipPath>
        </defs>
        <g clip-path='url(#outer_rectangle)'>
            <g style='isolation:isolate'>
                <path d='M 4.5 0 L 490.5 0 C 492.984 0 495 2.016 495 4.5 L 495 190.5 C 495 192.984 492.984 195 490.5 195 L 4.5 195 C 2.016 195 0 192.984 0 190.5 L 0 4.5 C 0 2.016 2.016 0 4.5 0 Z'
                    style='stroke: {$theme["border"]}; fill: {$theme["background"]};stroke-miterlimit:10;rx: 4.5;'/>
            </g>
            <g style='isolation:isolate'>
                <!-- Error Label -->
                <g transform='translate(166,108)'>
                    <rect width='163' height='50' stroke='none' fill='none'></rect>
                    <text x='81.5' y='50' dy='0.25em' stroke-width='0' text-anchor='middle' style='font-family:Segoe UI, Ubuntu, sans-serif;font-weight:400;font-size:14px;font-style:normal;fill:{$theme["sideLabels"]};stroke:none;'>
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

/**
 * Converts an SVG card to a PNG image
 *
 * @param string $svg The SVG for the card as a string
 *
 * @return string The generated PNG data
 *
 * @throws ImagickException
 */
function convertSvgToPng(string $svg): string
{
    // trim off all whitespaces to make it a valid SVG string
    $svg = trim($svg);

    // remove style and animations
    $svg = preg_replace("/(<style>\X*?<\/style>)/m", "", $svg);
    $svg = preg_replace("/(opacity: 0;)/m", "opacity: 1;", $svg);
    $svg = preg_replace("/(animation: fadein.*?;)/m", "opacity: 1;", $svg);
    $svg = preg_replace("/(animation: currentstreak.*?;)/m", "font-size: 28px;", $svg);
    $svg = preg_replace("/<a \X*?>(\X*?)<\/a>/m", '\1', $svg);

    // create canvas
    $imagick = new Imagick();
    $imagick->setBackgroundColor(new ImagickPixel("transparent"));

    // add svg image
    $imagick->setFormat("svg");
    $imagick->readImageBlob('<?xml version="1.0" encoding="UTF-8" standalone="no"?>' . $svg);
    $imagick->setFormat("png");

    // get PNG data
    $png = $imagick->getImageBlob();

    // clean up memory
    $imagick->clear();
    $imagick->destroy();

    return $png;
}

/**
 * Set headers and echo response based on type
 *
 * @param string|array $output The stats (array) or error message (string) to display
 */
function renderOutput(string|array $output, int $responseCode = 200): void
{
    $requestedType = $_REQUEST["type"] ?? "svg";
    http_response_code($responseCode);

    // output JSON data
    if ($requestedType === "json") {
        // set content type to JSON
        header("Content-Type: application/json");
        // generate array from output
        $data = gettype($output) === "string" ? ["error" => $output] : $output;
        // output as JSON
        echo json_encode($data);
    }
    // output SVG or PNG card
    else {
        // set content type to SVG or PNG
        header("Content-Type: image/" . ($requestedType === "png" ? "png" : "svg+xml"));
        // render SVG card
        $svg = gettype($output) === "string" ? generateErrorCard($output) : generateCard($output);
        // output PNG if PNG is requested, otherwise output SVG
        echo $requestedType === "png" ? convertSvgToPng($svg) : $svg;
    }
    exit();
}
