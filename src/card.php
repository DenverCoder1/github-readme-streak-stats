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
            $pattern = $patternGenerator->getBestPattern("yyyy MMM d");
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
 * @param array<string,string> $params Request parameters
 * @return array<string,string> The chosen theme or default
 */
function getRequestedTheme(array $params): array
{
    /**
     * @var array<string,array<string,string>> $THEMES
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
 * Wraps a string to a given number of characters
 *
 * Similar to `wordwrap()`, but uses regex and does not break with certain non-ascii characters
 *
 * @param string $string The input string
 * @param int $width The number of characters at which the string will be wrapped
 * @param string $break The line is broken using the optional `break` parameter
 * @param bool $cut_long_words If the `cut_long_words` parameter is set to true, the string is
 *              the string is always wrapped at or before the specified width. So if you have
 *              a word that is larger than the given width, it is broken apart.
 *              When false the function does not split the word even if the width is smaller
 *              than the word width.
 * @return string The given string wrapped at the specified length
 */
function utf8WordWrap(string $string, int $width = 75, string $break = "\n", bool $cut_long_words = false): string
{
    // match anything 1 to $width chars long followed by whitespace or EOS
    $string = preg_replace("/(.{1,$width})(?:\s|$)/uS", "$1$break", $string);
    // split words that are too long after being broken up
    if ($cut_long_words) {
        $string = preg_replace("/(\S{" . $width . "})(?=\S)/u", "$1$break", $string);
    }
    // trim any trailing line breaks
    return rtrim($string, $break);
}

/**
 * Get the length of a string with utf8 characters
 *
 * Similar to `strlen()`, but uses regex and does not break with certain non-ascii characters
 *
 * @param string $string The input string
 * @return int The length of the string
 */
function utf8Strlen(string $string): int
{
    return preg_match_all("/./us", $string, $matches);
}

/**
 * Split lines of text using <tspan> elements if it contains a newline or exceeds a maximum number of characters
 *
 * @param string $text Text to split
 * @param int $maxChars Maximum number of characters per line
 * @param int $line1Offset Offset for the first line
 * @return string Original text if one line, or split text with <tspan> elements
 */
function splitLines(string $text, int $maxChars, int $line1Offset): string
{
    // if too many characters, insert \n before a " " or "-" if possible
    if (utf8Strlen($text) > $maxChars && strpos($text, "\n") === false) {
        // prefer splitting at " - " if possible
        if (strpos($text, " - ") !== false) {
            $text = str_replace(" - ", "\n- ", $text);
        }
        // otherwise, use word wrap to split at spaces
        else {
            $text = utf8WordWrap($text, $maxChars, "\n", true);
        }
    }
    $text = htmlspecialchars($text);
    return preg_replace(
        "/^(.*)\n(.*)/",
        "<tspan x='81.5' dy='{$line1Offset}'>$1</tspan><tspan x='81.5' dy='16'>$2</tspan>",
        $text
    );
}

/**
 * Normalize a locale code
 *
 * @param string $localeCode Locale code
 * @return string Normalized locale code
 */
function normalizeLocaleCode(string $localeCode): string
{
    preg_match("/^([a-z]{2,3})(?:[_-]([a-z]{4}))?(?:[_-]([0-9]{3}|[a-z]{2}))?$/i", $localeCode, $matches);
    if (empty($matches)) {
        return "en";
    }
    $language = $matches[1];
    $script = $matches[2] ?? "";
    $region = $matches[3] ?? "";
    // convert language to lowercase
    $language = strtolower($language);
    // convert script to title case
    $script = ucfirst(strtolower($script));
    // convert region to uppercase
    $region = strtoupper($region);
    // combine language, script, and region using underscores
    return implode("_", array_filter([$language, $script, $region]));
}

/**
 * Get the translations for a locale code after normalizing it
 *
 * @param string $localeCode Locale code
 * @return array Translations for the locale code
 */
function getTranslations(string $localeCode): array
{
    // normalize locale code
    $localeCode = normalizeLocaleCode($localeCode);
    // get the labels from the translations file
    $translations = include "translations.php";
    // if the locale does not exist, try without the script and region
    if (!isset($translations[$localeCode])) {
        $localeCode = explode("_", $localeCode)[0];
    }
    // get the translations for the locale or empty array if it does not exist
    $localeTranslations = $translations[$localeCode] ?? [];
    // if the locale returned is a string, it is an alias for another locale
    if (is_string($localeTranslations)) {
        // get the translations for the alias
        $localeTranslations = $translations[$localeTranslations];
    }
    // fill in missing translations with English
    $localeTranslations += $translations["en"];
    // return the translations
    return $localeTranslations;
}

/**
 * Generate SVG output for a stats array
 *
 * @param array<string,mixed> $stats Streak stats
 * @param array<string,string>|NULL $params Request parameters
 * @return string The generated SVG Streak Stats card
 *
 * @throws InvalidArgumentException If a locale does not exist
 */
function generateCard(array $stats, array $params = null): string
{
    $params = $params ?? $_REQUEST;

    // get requested theme
    $theme = getRequestedTheme($params);

    // get requested locale, default to English
    $localeCode = $params["locale"] ?? "en";
    $localeTranslations = getTranslations($localeCode);

    // whether the locale is right-to-left
    $direction = $localeTranslations["rtl"] ?? false ? "rtl" : "ltr";

    // get date format
    // locale date formatter (used only if date_format is not specified)
    $dateFormat = $params["date_format"] ?? ($localeTranslations["date_format"] ?? null);

    // number formatter
    $numFormatter = new NumberFormatter($localeCode, NumberFormatter::DECIMAL);

    // read border_radius parameter, default to 4.5 if not set
    $borderRadius = $params["border_radius"] ?? "4.5";

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

    // if the translations contain a newline, split the text into two tspan elements
    $totalContributionsText = splitLines($localeTranslations["Total Contributions"], 22, -9);
    if ($stats["mode"] === "weekly") {
        $currentStreakText = splitLines($localeTranslations["Week Streak"], 22, -9);
        $longestStreakText = splitLines($localeTranslations["Longest Week Streak"], 22, -9);
    } else {
        $currentStreakText = splitLines($localeTranslations["Current Streak"], 22, -9);
        $longestStreakText = splitLines($localeTranslations["Longest Streak"], 22, -9);
    }

    // if the ranges contain over 28 characters, split the text into two tspan elements
    $totalContributionsRange = splitLines($totalContributionsRange, 28, 0);
    $currentStreakRange = splitLines($currentStreakRange, 28, 0);
    $longestStreakRange = splitLines($longestStreakRange, 28, 0);

    return "<svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'
                style='isolation: isolate' viewBox='0 0 495 195' width='495px' height='195px' direction='{$direction}'>
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
                <rect width='495' height='195' rx='{$borderRadius}'/>
            </clipPath>
            <mask id='mask_out_ring_behind_fire'>
                <rect width='495' height='195' fill='white'/>
                <ellipse id='mask-ellipse' cx='247.5' cy='32' rx='13' ry='18' fill='black'/>
            </mask>
        </defs>
        <g clip-path='url(#outer_rectangle)'>
            <g style='isolation: isolate'>
                <rect stroke='{$theme["border"]}' fill='{$theme["background"]}' rx='{$borderRadius}' x='0.5' y='0.5' width='494' height='194'/>
            </g>
            <g style='isolation: isolate'>
                <line x1='330' y1='28' x2='330' y2='170' vector-effect='non-scaling-stroke' stroke-width='1' stroke='{$theme["stroke"]}' stroke-linejoin='miter' stroke-linecap='square' stroke-miterlimit='3'/>
                <line x1='165' y1='28' x2='165' y2='170' vector-effect='non-scaling-stroke' stroke-width='1' stroke='{$theme["stroke"]}' stroke-linejoin='miter' stroke-linecap='square' stroke-miterlimit='3'/>
            </g>
            <g style='isolation: isolate'>
                <!-- Total Contributions Big Number -->
                <g transform='translate(1,48)'>
                    <text x='81.5' y='32' stroke-width='0' text-anchor='middle' fill='{$theme["sideNums"]}' stroke='none' font-family='\"Segoe UI\", Ubuntu, sans-serif' font-weight='700' font-size='28px' font-style='normal' style='opacity: 0; animation: fadein 0.5s linear forwards 0.6s'>
                        {$totalContributions}
                    </text>
                </g>

                <!-- Total Contributions Label -->
                <g transform='translate(1,84)'>
                    <text x='81.5' y='32' stroke-width='0' text-anchor='middle' fill='{$theme["sideLabels"]}' stroke='none' font-family='\"Segoe UI\", Ubuntu, sans-serif' font-weight='400' font-size='14px' font-style='normal' style='opacity: 0; animation: fadein 0.5s linear forwards 0.7s'>
                        {$totalContributionsText}
                    </text>
                </g>

                <!-- total contributions range -->
                <g transform='translate(1,114)'>
                    <text x='81.5' y='32' stroke-width='0' text-anchor='middle' fill='{$theme["dates"]}' stroke='none' font-family='\"Segoe UI\", Ubuntu, sans-serif' font-weight='400' font-size='12px' font-style='normal' style='opacity: 0; animation: fadein 0.5s linear forwards 0.8s'>
                        {$totalContributionsRange}
                    </text>
                </g>
            </g>
            <g style='isolation: isolate'>
                <!-- Current Streak Big Number -->
                <g transform='translate(166,48)'>
                    <text x='81.5' y='32' stroke-width='0' text-anchor='middle' fill='{$theme["currStreakNum"]}' stroke='none' font-family='\"Segoe UI\", Ubuntu, sans-serif' font-weight='700' font-size='28px' font-style='normal' style='animation: currstreak 0.6s linear forwards'>
                        {$currentStreak}
                    </text>
                </g>

                <!-- Current Streak Label -->
                <g transform='translate(166,108)'>
                    <text x='81.5' y='32' stroke-width='0' text-anchor='middle' fill='{$theme["currStreakLabel"]}' stroke='none' font-family='\"Segoe UI\", Ubuntu, sans-serif' font-weight='700' font-size='14px' font-style='normal' style='opacity: 0; animation: fadein 0.5s linear forwards 0.9s'>
                        {$currentStreakText}
                    </text>
                </g>

                <!-- Current Streak Range -->
                <g transform='translate(166,145)'>
                    <text x='81.5' y='21' stroke-width='0' text-anchor='middle' fill='{$theme["dates"]}' stroke='none' font-family='\"Segoe UI\", Ubuntu, sans-serif' font-weight='400' font-size='12px' font-style='normal' style='opacity: 0; animation: fadein 0.5s linear forwards 0.9s'>
                        {$currentStreakRange}
                    </text>
                </g>

                <!-- ring around number -->
                <g mask='url(#mask_out_ring_behind_fire)'>
                    <circle cx='247.5' cy='71' r='40' fill='none' stroke='{$theme["ring"]}' stroke-width='5' style='opacity: 0; animation: fadein 0.5s linear forwards 0.4s'></circle>
                </g>
                <!-- fire icon -->
                <g stroke-opacity='0' style='opacity: 0; animation: fadein 0.5s linear forwards 0.6s'>
                    <path d=' M 235.5 19.5 L 259.5 19.5 L 259.5 43.5 L 235.5 43.5 L 235.5 19.5 Z ' fill='none'/>
                    <path d=' M 249 20.17 C 249 20.17 249.74 22.82 249.74 24.97 C 249.74 27.03 248.39 28.7 246.33 28.7 C 244.26 28.7 242.7 27.03 242.7 24.97 L 242.73 24.61 C 240.71 27.01 239.5 30.12 239.5 33.5 C 239.5 37.92 243.08 41.5 247.5 41.5 C 251.92 41.5 255.5 37.92 255.5 33.5 C 255.5 28.11 252.91 23.3 249 20.17 Z  M 247.21 38.5 C 245.43 38.5 243.99 37.1 243.99 35.36 C 243.99 33.74 245.04 32.6 246.8 32.24 C 248.57 31.88 250.4 31.03 251.42 29.66 C 251.81 30.95 252.01 32.31 252.01 33.7 C 252.01 36.35 249.86 38.5 247.21 38.5 Z ' fill='{$theme["fire"]}' stroke-opacity='0'/>
                </g>

            </g>
            <g style='isolation: isolate'>
                <!-- Longest Streak Big Number -->
                <g transform='translate(331,48)'>
                    <text x='81.5' y='32' stroke-width='0' text-anchor='middle' fill='{$theme["sideNums"]}' stroke='none' font-family='\"Segoe UI\", Ubuntu, sans-serif' font-weight='700' font-size='28px' font-style='normal' style='opacity: 0; animation: fadein 0.5s linear forwards 1.2s'>
                        {$longestStreak}
                    </text>
                </g>

                <!-- Longest Streak Label -->
                <g transform='translate(331,84)'>
                    <text x='81.5' y='32' stroke-width='0' text-anchor='middle' fill='{$theme["sideLabels"]}' stroke='none' font-family='\"Segoe UI\", Ubuntu, sans-serif' font-weight='400' font-size='14px' font-style='normal' style='opacity: 0; animation: fadein 0.5s linear forwards 1.3s'>
                        {$longestStreakText}
                    </text>
                </g>

                <!-- Longest Streak Range -->
                <g transform='translate(331,114)'>
                    <text x='81.5' y='32' stroke-width='0' text-anchor='middle' fill='{$theme["dates"]}' stroke='none' font-family='\"Segoe UI\", Ubuntu, sans-serif' font-weight='400' font-size='12px' font-style='normal' style='opacity: 0; animation: fadein 0.5s linear forwards 1.4s'>
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
 * @param array<string,string>|NULL $params Request parameters
 * @return string The generated SVG error card
 */
function generateErrorCard(string $message, array $params = null): string
{
    $params = $params ?? $_REQUEST;

    // get requested theme, use $_REQUEST if no params array specified
    $theme = getRequestedTheme($params);

    // read border_radius parameter, default to 4.5 if not set
    $borderRadius = $params["border_radius"] ?? "4.5";

    return "<svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' style='isolation: isolate' viewBox='0 0 495 195' width='495px' height='195px'>
        <style>
            a {
                fill: {$theme["dates"]};
            }
        </style>
        <defs>
            <clipPath id='outer_rectangle'>
                <rect width='495' height='195' rx='{$borderRadius}'/>
            </clipPath>
        </defs>
        <g clip-path='url(#outer_rectangle)'>
            <g style='isolation: isolate'>
                <rect stroke='{$theme["border"]}' fill='{$theme["background"]}' rx='{$borderRadius}' x='0.5' y='0.5' width='494' height='194'/>
            </g>
            <g style='isolation: isolate'>
                <!-- Error Label -->
                <g transform='translate(166,108)'>
                    <text x='81.5' y='50' dy='0.25em' stroke-width='0' text-anchor='middle' fill='{$theme["sideLabels"]}' stroke='none' font-family='\"Segoe UI\", Ubuntu, sans-serif' font-weight='400' font-size='14px' font-style='normal'>
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
                    <path fill='{$theme["fire"]}' d='M248,35.8c-25.2,0-45.7,20.5-45.7,45.7s20.5,45.8,45.7,45.8s45.7-20.5,45.7-45.7S273.2,35.8,248,35.8z M248,122.3c-11.2,0-21.4-4.5-28.8-11.9c-2.9-2.9-5.4-6.3-7.4-10c-3-5.7-4.6-12.1-4.6-18.9c0-22.5,18.3-40.8,40.8-40.8 c10.7,0,20.4,4.1,27.7,10.9c3.8,3.5,6.9,7.7,9.1,12.4c2.6,5.3,4,11.3,4,17.6C288.8,104.1,270.5,122.3,248,122.3z'/>
                    <path fill='{$theme["fire"]}' d='M252.8,93.8c5.4,1.1,10.3,4.2,13.7,8.6l3.9-3c-4.1-5.3-10-9-16.6-10.4c-10.6-2.2-21.7,1.9-28.3,10.4l3.9,3 C234.9,95.3,244.1,91.9,252.8,93.8z'/>
                    <circle fill='{$theme["fire"]}' cx='232.8' cy='71.3' r='4.9'/>
                    <circle fill='{$theme["fire"]}' cx='263.4' cy='71.3' r='4.9'/>
                </g>
            </g>
        </g>
    </svg>
";
}

/**
 * Remove animations from SVG
 *
 * @param string $svg The SVG for the card as a string
 * @return string The SVG without animations
 */
function removeAnimations(string $svg): string
{
    $svg = preg_replace("/(<style>\X*?<\/style>)/m", "", $svg);
    $svg = preg_replace("/(opacity: 0;)/m", "opacity: 1;", $svg);
    $svg = preg_replace("/(animation: fadein[^;'\"]+)/m", "opacity: 1;", $svg);
    $svg = preg_replace("/(animation: currstreak[^;'\"]+)/m", "font-size: 28px;", $svg);
    $svg = preg_replace("/<a \X*?>(\X*?)<\/a>/m", '\1', $svg);
    return $svg;
}

/**
 * Convert a color from hex 3/4/6/8 digits to hex 6 digits and opacity (0-1)
 *
 * @param string $color The color to convert
 * @return array<string, string> The converted color
 */
function convertHexColor(string $color): array
{
    $color = preg_replace("/[^0-9a-fA-F]/", "", $color);

    // double each character if the color is in 3/4 digit format
    if (strlen($color) === 3) {
        $chars = str_split($color);
        $color = "{$chars[0]}{$chars[0]}{$chars[1]}{$chars[1]}{$chars[2]}{$chars[2]}";
    } elseif (strlen($color) === 4) {
        $chars = str_split($color);
        $color = "{$chars[0]}{$chars[0]}{$chars[1]}{$chars[1]}{$chars[2]}{$chars[2]}{$chars[3]}{$chars[3]}";
    }

    // convert to 6 digit hex and opacity
    if (strlen($color) === 6) {
        return [
            "color" => "#{$color}",
            "opacity" => 1,
        ];
    } elseif (strlen($color) === 8) {
        return [
            "color" => "#" . substr($color, 0, 6),
            "opacity" => hexdec(substr($color, 6, 2)) / 255,
        ];
    }
    throw new AssertionError("Invalid color: " . $color);
}

/**
 * Convert transparent hex colors (4/8 digits) in an SVG to hex 6 digits and corresponding opacity attribute (0-1)
 *
 * @param string $svg The SVG for the card as a string
 * @return string The SVG with converted colors
 */
function convertHexColors(string $svg): string
{
    // convert "transparent" to "#0000"
    $svg = preg_replace("/(fill|stroke)=['\"]transparent['\"]/m", '\1="#0000"', $svg);

    // convert hex colors to 6 digits and corresponding opacity attribute
    $svg = preg_replace_callback(
        "/(fill|stroke)=['\"]#([0-9a-fA-F]{4}|[0-9a-fA-F]{8})['\"]/m",
        function ($matches) {
            $attribute = $matches[1];
            $result = convertHexColor($matches[2]);
            $color = $result["color"];
            $opacity = $result["opacity"];
            return "{$attribute}='{$color}' {$attribute}-opacity='{$opacity}'";
        },
        $svg
    );

    return $svg;
}

/**
 * Converts an SVG card to a PNG image
 *
 * @param string $svg The SVG for the card as a string
 * @return string The generated PNG data
 */
function convertSvgToPng(string $svg): string
{
    // trim off all whitespaces to make it a valid SVG string
    $svg = trim($svg);

    // remove style and animations
    $svg = removeAnimations($svg);

    // escape svg for shell
    $svg = escapeshellarg($svg);

    // `--pipe`: read input from pipe (stdin)
    // `--export-filename -`: write output to stdout
    // `-w 495 -h 195`: set width and height of the output image
    // `--export-type png`: set the output format to PNG
    $cmd = "echo {$svg} | inkscape --pipe --export-filename - -w 495 -h 195 --export-type png";

    // convert svg to png
    $png = shell_exec($cmd); // skipcq: PHP-A1009

    // check if the conversion was successful
    if (empty($png)) {
        // `2>&1`: redirect stderr to stdout
        $error = shell_exec("$cmd 2>&1"); // skipcq: PHP-A1009
        throw new InvalidArgumentException("Failed to convert SVG to PNG: {$error}", 500);
    }

    // return the generated png
    return $png;
}

/**
 * Return headers and response based on type
 *
 * @param string|array $output The stats (array) or error message (string) to display
 * @param array<string,string>|NULL $params Request parameters
 * @return array The Content-Type header and the response body, and status code in case of an error
 */
function generateOutput(string|array $output, array $params = null): array
{
    $params = $params ?? $_REQUEST;

    $requestedType = $params["type"] ?? "svg";

    // output JSON data
    if ($requestedType === "json") {
        // generate array from output
        $data = gettype($output) === "string" ? ["error" => $output] : $output;
        return [
            "contentType" => "application/json",
            "body" => json_encode($data),
        ];
    }

    // generate SVG card
    $svg = gettype($output) === "string" ? generateErrorCard($output, $params) : generateCard($output, $params);

    // some renderers such as inkscape doesn't support transparent colors in hex format, so we need to convert them
    $svg = convertHexColors($svg);

    // output PNG card
    if ($requestedType === "png") {
        try {
            $png = convertSvgToPng($svg);
            return [
                "contentType" => "image/png",
                "body" => $png,
            ];
        } catch (Exception $e) {
            return [
                "contentType" => "image/svg+xml",
                "status" => 500,
                "body" => generateErrorCard($e->getMessage(), $params),
            ];
        }
    }

    // remove animations if disable_animations is set
    if (isset($params["disable_animations"]) && $params["disable_animations"] == "true") {
        $svg = removeAnimations($svg);
    }

    // output SVG card
    return [
        "contentType" => "image/svg+xml",
        "body" => $svg,
    ];
}

/**
 * Set headers and output response
 *
 * @param string|array $output The Content-Type header and the response body
 * @param int $responseCode The HTTP response code to send
 * @return void The function exits after sending the response
 */
function renderOutput(string|array $output, int $responseCode = 200): void
{
    $response = generateOutput($output);
    http_response_code($response["status"] ?? $responseCode);
    header("Content-Type: {$response["contentType"]}");
    exit($response["body"]);
}
