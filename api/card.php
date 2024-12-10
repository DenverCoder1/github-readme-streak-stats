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
 * Translate days of the week
 *
 * Takes a list of days (eg. ["Sun", "Mon", "Sat"]) and returns the short abbreviation of the days of the week in another locale
 * e.g. ["Sun", "Mon", "Sat"] -> ["dim", "lun", "sam"]
 *
 * @param array<string> $days List of days to translate
 * @param string $locale Locale code
 *
 * @return array<string> Translated days
 */
function translateDays(array $days, string $locale): array
{
    if ($locale === "en") {
        return $days;
    }
    $patternGenerator = new IntlDatePatternGenerator($locale);
    $pattern = $patternGenerator->getBestPattern("EEE");
    $dateFormatter = new IntlDateFormatter(
        $locale,
        IntlDateFormatter::NONE,
        IntlDateFormatter::NONE,
        pattern: $pattern
    );
    $translatedDays = [];
    foreach ($days as $day) {
        $translatedDays[] = $dateFormatter->format(new DateTime($day));
    }
    return $translatedDays;
}

/**
 * Get the excluding days text
 *
 * @param array<string> $excludedDays List of excluded days
 * @param array<string,string> $localeTranslations Translations for the locale
 * @param string $localeCode Locale code
 * @return string Excluding days text
 */
function getExcludingDaysText($excludedDays, $localeTranslations, $localeCode)
{
    $separator = $localeTranslations["comma_separator"] ?? ", ";
    $daysCommaSeparated = implode($separator, translateDays($excludedDays, $localeCode));
    return str_replace("{days}", $daysCommaSeparated, $localeTranslations["Excluding {days}"]);
}

/**
 * Normalize a theme name
 *
 * @param string $theme Theme name
 * @return string Normalized theme name
 */
function normalizeThemeName(string $theme): string
{
    return strtolower(str_replace("_", "-", $theme));
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

    // normalize theme name
    $selectedTheme = normalizeThemeName($params["theme"] ?? "default");

    // get theme colors, or default colors if theme not found
    $theme = $THEMES[$selectedTheme] ?? $THEMES["default"];

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
            // if the property is background gradient is allowed (angle,start_color,...,end_color)
            elseif ($prop == "background" && preg_match("/^-?[0-9]+,[a-f0-9]{3,8}(,[a-f0-9]{3,8})+$/", $param)) {
                // set property
                $theme[$prop] = $param;
            }
        }
    }

    // hide borders
    if (isset($params["hide_border"]) && $params["hide_border"] == "true") {
        $theme["border"] = "#0000"; // transparent
    }

    // set background
    $gradient = "";
    $backgroundParts = explode(",", $theme["background"] ?? "");
    if (count($backgroundParts) >= 3) {
        $theme["background"] = "url(#gradient)";
        $gradient = "<linearGradient id='gradient' gradientTransform='rotate({$backgroundParts[0]})' gradientUnits='userSpaceOnUse'>";
        $backgroundColors = array_slice($backgroundParts, 1);
        $colorCount = count($backgroundColors);
        for ($index = 0; $index < $colorCount; $index++) {
            $offset = ($index * 100) / ($colorCount - 1);
            $gradient .= "<stop offset='{$offset}%' stop-color='#{$backgroundColors[$index]}' />";
        }
        $gradient .= "</linearGradient>";
    }
    $theme["backgroundGradient"] = $gradient;

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
    if ($maxChars > 0 && utf8Strlen($text) > $maxChars && strpos($text, "\n") === false) {
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
        "<tspan x='0' dy='{$line1Offset}'>$1</tspan><tspan x='0' dy='16'>$2</tspan>",
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
 * Get the card width from params taking into account minimum and default values
 *
 * @param array<string,string> $params Request parameters
 * @param int $numColumns Number of columns in the card
 * @return int Card width
 */
function getCardWidth(array $params, int $numColumns = 3): int
{
    $defaultWidth = 495;
    $minimumWidth = 100 * $numColumns;
    return max($minimumWidth, intval($params["card_width"] ?? $defaultWidth));
}

/**
 * Get the card height from params taking into account minimum and default values
 *
 * @param array<string,string> $params Request parameters
 * @return int Card width
 */
function getCardHeight(array $params): int
{
    $defaultHeight = 195;
    $minimumHeight = 170;
    return max($minimumHeight, intval($params["card_height"] ?? $defaultHeight));
}

/**
 * Format number using locale and short number if requested
 *
 * @param float $num The number to format
 * @param string $localeCode Locale code
 * @param bool $useShortNumbers Whether to use short numbers
 * @return string The formatted number
 */
function formatNumber(float $num, string $localeCode, bool $useShortNumbers): string
{
    $numFormatter = new NumberFormatter($localeCode, NumberFormatter::DECIMAL);
    $suffix = "";
    if ($useShortNumbers) {
        $units = ["", "K", "M", "B", "T"];
        for ($i = 0; $num >= 1000; $i++) {
            $num /= 1000;
        }
        $suffix = $units[$i];
        $num = round($num, 1);
    }
    return $numFormatter->format($num) . $suffix;
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

    // read border_radius parameter, default to 4.5 if not set
    $borderRadius = $params["border_radius"] ?? 4.5;

    $showTotalContributions = ($params["hide_total_contributions"] ?? "") !== "true";
    $showCurrentStreak = ($params["hide_current_streak"] ?? "") !== "true";
    $showLongestStreak = ($params["hide_longest_streak"] ?? "") !== "true";
    $numColumns = intval($showTotalContributions) + intval($showCurrentStreak) + intval($showLongestStreak);

    $cardWidth = getCardWidth($params, $numColumns);
    $rectWidth = $cardWidth - 1;
    $columnWidth = $numColumns > 0 ? $cardWidth / $numColumns : 0;

    $cardHeight = getCardHeight($params);
    $rectHeight = $cardHeight - 1;
    $heightOffset = ($cardHeight - 195) / 2;

    // X offsets for the bars between columns
    $barOffsets = [-999, -999];
    for ($i = 0; $i < $numColumns - 1; $i++) {
        $barOffsets[$i] = $columnWidth * ($i + 1);
    }
    // offsets for the text in each column
    $columnOffsets = [];
    for ($i = 0; $i < $numColumns; $i++) {
        $columnOffsets[] = $columnWidth / 2 + $columnWidth * $i;
    }
    // reverse the column offsets if the locale is right-to-left
    if ($direction === "rtl") {
        $columnOffsets = array_reverse($columnOffsets);
    }

    $nextColumnIndex = 0;
    $totalContributionsOffset = $showTotalContributions ? $columnOffsets[$nextColumnIndex++] : -999;
    $currentStreakOffset = $showCurrentStreak ? $columnOffsets[$nextColumnIndex++] : -999;
    $longestStreakOffset = $showLongestStreak ? $columnOffsets[$nextColumnIndex++] : -999;

    // Y offsets for the bars
    $barHeightOffsets = [28 + $heightOffset / 2, 170 + $heightOffset];
    // Y offsets for the numbers and dates
    $longestStreakHeightOffset = $totalContributionsHeightOffset = [
        48 + $heightOffset,
        84 + $heightOffset,
        114 + $heightOffset,
    ];
    $currentStreakHeightOffset = [
        48 + $heightOffset,
        108 + $heightOffset,
        145 + $heightOffset,
        71 + $heightOffset,
        19.5 + $heightOffset,
    ];

    $useShortNumbers = ($params["short_numbers"] ?? "") === "true";

    // total contributions
    $totalContributions = formatNumber($stats["totalContributions"], $localeCode, $useShortNumbers);
    $firstContribution = formatDate($stats["firstContribution"], $dateFormat, $localeCode);
    $totalContributionsRange = $firstContribution . " - " . $localeTranslations["Present"];

    // current streak
    $currentStreak = formatNumber($stats["currentStreak"]["length"], $localeCode, $useShortNumbers);
    $currentStreakStart = formatDate($stats["currentStreak"]["start"], $dateFormat, $localeCode);
    $currentStreakEnd = formatDate($stats["currentStreak"]["end"], $dateFormat, $localeCode);
    $currentStreakRange = $currentStreakStart;
    if ($currentStreakStart != $currentStreakEnd) {
        $currentStreakRange .= " - " . $currentStreakEnd;
    }

    // longest streak
    $longestStreak = formatNumber($stats["longestStreak"]["length"], $localeCode, $useShortNumbers);
    $longestStreakStart = formatDate($stats["longestStreak"]["start"], $dateFormat, $localeCode);
    $longestStreakEnd = formatDate($stats["longestStreak"]["end"], $dateFormat, $localeCode);
    $longestStreakRange = $longestStreakStart;
    if ($longestStreakStart != $longestStreakEnd) {
        $longestStreakRange .= " - " . $longestStreakEnd;
    }

    // if the translations contain over max characters or a newline, split the text into two tspan elements
    $maxCharsPerLineLabels = $numColumns > 0 ? intval(floor($cardWidth / $numColumns / 7.5)) : 0;
    $totalContributionsText = splitLines($localeTranslations["Total Contributions"], $maxCharsPerLineLabels, -9);
    if ($stats["mode"] === "weekly") {
        $currentStreakText = splitLines($localeTranslations["Week Streak"], $maxCharsPerLineLabels, -9);
        $longestStreakText = splitLines($localeTranslations["Longest Week Streak"], $maxCharsPerLineLabels, -9);
    } else {
        $currentStreakText = splitLines($localeTranslations["Current Streak"], $maxCharsPerLineLabels, -9);
        $longestStreakText = splitLines($localeTranslations["Longest Streak"], $maxCharsPerLineLabels, -9);
    }

    // if the ranges contain over max characters, split the text into two tspan elements
    $maxCharsPerLineDates = $numColumns > 0 ? intval(floor($cardWidth / $numColumns / 6)) : 0;
    $totalContributionsRange = splitLines($totalContributionsRange, $maxCharsPerLineDates, 0);
    $currentStreakRange = splitLines($currentStreakRange, $maxCharsPerLineDates, 0);
    $longestStreakRange = splitLines($longestStreakRange, $maxCharsPerLineDates, 0);

    // if days are excluded, add a note to the corner
    $excludedDays = "";
    if (!empty($stats["excludedDays"])) {
        $offset = $direction === "rtl" ? $cardWidth - 5 : 5;
        $excludingDaysText = getExcludingDaysText($stats["excludedDays"], $localeTranslations, $localeCode);
        $excludedDays = "<g style='isolation: isolate'>
                <!-- Excluded Days -->
                <g transform='translate({$offset},187)'>
                    <text stroke-width='0' text-anchor='right' fill='{$theme["excludeDaysLabel"]}' stroke='none' font-family='\"Segoe UI\", Ubuntu, sans-serif' font-weight='400' font-size='10px' font-style='normal' style='opacity: 0; animation: fadein 0.5s linear forwards 0.9s'>
                        * {$excludingDaysText}
                    </text>
                </g>
            </g>";
    }

    return "<svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'
                style='isolation: isolate' viewBox='0 0 {$cardWidth} {$cardHeight}' width='{$cardWidth}px' height='{$cardHeight}px' direction='{$direction}'>
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
                <rect width='{$cardWidth}' height='{$cardHeight}' rx='{$borderRadius}'/>
            </clipPath>
            <mask id='mask_out_ring_behind_fire'>
                <rect width='{$cardWidth}' height='{$cardHeight}' fill='white'/>
                <ellipse id='mask-ellipse' cx='{$currentStreakOffset}' cy='32' rx='13' ry='18' fill='black'/>
            </mask>
            {$theme["backgroundGradient"]}
        </defs>
        <g clip-path='url(#outer_rectangle)'>
            <g style='isolation: isolate'>
                <rect stroke='{$theme["border"]}' fill='{$theme["background"]}' rx='{$borderRadius}' x='0.5' y='0.5' width='{$rectWidth}' height='{$rectHeight}'/>
            </g>
            <g style='isolation: isolate'>
                <line x1='{$barOffsets[0]}' y1='{$barHeightOffsets[0]}' x2='{$barOffsets[0]}' y2='{$barHeightOffsets[1]}' vector-effect='non-scaling-stroke' stroke-width='1' stroke='{$theme["stroke"]}' stroke-linejoin='miter' stroke-linecap='square' stroke-miterlimit='3'/>
                <line x1='{$barOffsets[1]}' y1='$barHeightOffsets[0]' x2='{$barOffsets[1]}' y2='$barHeightOffsets[1]' vector-effect='non-scaling-stroke' stroke-width='1' stroke='{$theme["stroke"]}' stroke-linejoin='miter' stroke-linecap='square' stroke-miterlimit='3'/>
            </g>
            <g style='isolation: isolate'>
                <!-- Total Contributions big number -->
                <g transform='translate({$totalContributionsOffset}, {$totalContributionsHeightOffset[0]})'>
                    <text x='0' y='32' stroke-width='0' text-anchor='middle' fill='{$theme["sideNums"]}' stroke='none' font-family='\"Segoe UI\", Ubuntu, sans-serif' font-weight='700' font-size='28px' font-style='normal' style='opacity: 0; animation: fadein 0.5s linear forwards 0.6s'>
                        {$totalContributions}
                    </text>
                </g>

                <!-- Total Contributions label -->
                <g transform='translate({$totalContributionsOffset}, {$totalContributionsHeightOffset[1]})'>
                    <text x='0' y='32' stroke-width='0' text-anchor='middle' fill='{$theme["sideLabels"]}' stroke='none' font-family='\"Segoe UI\", Ubuntu, sans-serif' font-weight='400' font-size='14px' font-style='normal' style='opacity: 0; animation: fadein 0.5s linear forwards 0.7s'>
                        {$totalContributionsText}
                    </text>
                </g>

                <!-- Total Contributions range -->
                <g transform='translate({$totalContributionsOffset}, {$totalContributionsHeightOffset[2]})'>
                    <text x='0' y='32' stroke-width='0' text-anchor='middle' fill='{$theme["dates"]}' stroke='none' font-family='\"Segoe UI\", Ubuntu, sans-serif' font-weight='400' font-size='12px' font-style='normal' style='opacity: 0; animation: fadein 0.5s linear forwards 0.8s'>
                        {$totalContributionsRange}
                    </text>
                </g>
            </g>
            <g style='isolation: isolate'>
                <!-- Current Streak big number -->
                <g transform='translate({$currentStreakOffset}, {$currentStreakHeightOffset[0]})'>
                    <text x='0' y='32' stroke-width='0' text-anchor='middle' fill='{$theme["currStreakNum"]}' stroke='none' font-family='\"Segoe UI\", Ubuntu, sans-serif' font-weight='700' font-size='28px' font-style='normal' style='animation: currstreak 0.6s linear forwards'>
                        {$currentStreak}
                    </text>
                </g>

                <!-- Current Streak label -->
                <g transform='translate({$currentStreakOffset}, {$currentStreakHeightOffset[1]})'>
                    <text x='0' y='32' stroke-width='0' text-anchor='middle' fill='{$theme["currStreakLabel"]}' stroke='none' font-family='\"Segoe UI\", Ubuntu, sans-serif' font-weight='700' font-size='14px' font-style='normal' style='opacity: 0; animation: fadein 0.5s linear forwards 0.9s'>
                        {$currentStreakText}
                    </text>
                </g>

                <!-- Current Streak range -->
                <g transform='translate({$currentStreakOffset}, {$currentStreakHeightOffset[2]})'>
                    <text x='0' y='21' stroke-width='0' text-anchor='middle' fill='{$theme["dates"]}' stroke='none' font-family='\"Segoe UI\", Ubuntu, sans-serif' font-weight='400' font-size='12px' font-style='normal' style='opacity: 0; animation: fadein 0.5s linear forwards 0.9s'>
                        {$currentStreakRange}
                    </text>
                </g>

                <!-- Ring around number -->
                <g mask='url(#mask_out_ring_behind_fire)'>
                    <circle cx='{$currentStreakOffset}' cy='{$currentStreakHeightOffset[3]}' r='40' fill='none' stroke='{$theme["ring"]}' stroke-width='5' style='opacity: 0; animation: fadein 0.5s linear forwards 0.4s'></circle>
                </g>
                <!-- Fire icon -->
                <g transform='translate({$currentStreakOffset}, {$currentStreakHeightOffset[4]})' stroke-opacity='0' style='opacity: 0; animation: fadein 0.5s linear forwards 0.6s'>
                    <path d='M -12 -0.5 L 15 -0.5 L 15 23.5 L -12 23.5 L -12 -0.5 Z' fill='none'/>
                    <path d='M 1.5 0.67 C 1.5 0.67 2.24 3.32 2.24 5.47 C 2.24 7.53 0.89 9.2 -1.17 9.2 C -3.23 9.2 -4.79 7.53 -4.79 5.47 L -4.76 5.11 C -6.78 7.51 -8 10.62 -8 13.99 C -8 18.41 -4.42 22 0 22 C 4.42 22 8 18.41 8 13.99 C 8 8.6 5.41 3.79 1.5 0.67 Z M -0.29 19 C -2.07 19 -3.51 17.6 -3.51 15.86 C -3.51 14.24 -2.46 13.1 -0.7 12.74 C 1.07 12.38 2.9 11.53 3.92 10.16 C 4.31 11.45 4.51 12.81 4.51 14.2 C 4.51 16.85 2.36 19 -0.29 19 Z' fill='{$theme["fire"]}' stroke-opacity='0'/>
                </g>

            </g>
            <g style='isolation: isolate'>
                <!-- Longest Streak big number -->
                <g transform='translate({$longestStreakOffset}, {$longestStreakHeightOffset[0]})'>
                    <text x='0' y='32' stroke-width='0' text-anchor='middle' fill='{$theme["sideNums"]}' stroke='none' font-family='\"Segoe UI\", Ubuntu, sans-serif' font-weight='700' font-size='28px' font-style='normal' style='opacity: 0; animation: fadein 0.5s linear forwards 1.2s'>
                        {$longestStreak}
                    </text>
                </g>

                <!-- Longest Streak label -->
                <g transform='translate({$longestStreakOffset}, {$longestStreakHeightOffset[1]})'>
                    <text x='0' y='32' stroke-width='0' text-anchor='middle' fill='{$theme["sideLabels"]}' stroke='none' font-family='\"Segoe UI\", Ubuntu, sans-serif' font-weight='400' font-size='14px' font-style='normal' style='opacity: 0; animation: fadein 0.5s linear forwards 1.3s'>
                        {$longestStreakText}
                    </text>
                </g>

                <!-- Longest Streak range -->
                <g transform='translate({$longestStreakOffset}, {$longestStreakHeightOffset[2]})'>
                    <text x='0' y='32' stroke-width='0' text-anchor='middle' fill='{$theme["dates"]}' stroke='none' font-family='\"Segoe UI\", Ubuntu, sans-serif' font-weight='400' font-size='12px' font-style='normal' style='opacity: 0; animation: fadein 0.5s linear forwards 1.4s'>
                        {$longestStreakRange}
                    </text>
                </g>
            </g>
            {$excludedDays}
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
    $borderRadius = $params["border_radius"] ?? 4.5;

    // read card_width parameter
    $cardWidth = getCardWidth($params);
    $rectWidth = $cardWidth - 1;
    $centerOffset = $cardWidth / 2;

    // read card_height parameter
    $cardHeight = getCardHeight($params);
    $rectHeight = $cardHeight - 1;
    $heightOffset = ($cardHeight - 195) / 2;
    $errorLabelOffset = $cardHeight / 2 + 10.5;

    return "<svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' style='isolation: isolate' viewBox='0 0 {$cardWidth} {$cardHeight}' width='{$cardWidth}px' height='{$cardHeight}px'>
        <style>
            a {
                fill: {$theme["dates"]};
            }
        </style>
        <defs>
            <clipPath id='outer_rectangle'>
                <rect width='{$cardWidth}' height='{$cardHeight}' rx='{$borderRadius}'/>
            </clipPath>
            {$theme["backgroundGradient"]}
        </defs>
        <g clip-path='url(#outer_rectangle)'>
            <g style='isolation: isolate'>
                <rect stroke='{$theme["border"]}' fill='{$theme["background"]}' rx='{$borderRadius}' x='0.5' y='0.5' width='{$rectWidth}' height='{$rectHeight}'/>
            </g>
            <g style='isolation: isolate'>
                <!-- Error lable -->
                <g transform='translate({$centerOffset}, {$errorLabelOffset})'>
                    <text x='0' y='50' dy='0.25em' stroke-width='0' text-anchor='middle' fill='{$theme["sideLabels"]}' stroke='none' font-family='\"Segoe UI\", Ubuntu, sans-serif' font-weight='400' font-size='14px' font-style='normal'>
                        {$message}
                    </text>
                </g>

                <!-- Mask for background behind face -->
                <defs>
                    <mask id='cut-off-area'>
                        <rect x='0' y='0' width='500' height='500' fill='white' />
                        <ellipse cx='{$centerOffset}' cy='31' rx='13' ry='18'/>
                    </mask>
                </defs>
                <!-- Sad face -->
                <g transform='translate({$centerOffset}, {$heightOffset})'>
                    <path fill='{$theme["fire"]}' d='M0,35.8c-25.2,0-45.7,20.5-45.7,45.7s20.5,45.8,45.7,45.8s45.7-20.5,45.7-45.7S25.2,35.8,0,35.8z M0,122.3c-11.2,0-21.4-4.5-28.8-11.9c-2.9-2.9-5.4-6.3-7.4-10c-3-5.7-4.6-12.1-4.6-18.9c0-22.5,18.3-40.8,40.8-40.8 c10.7,0,20.4,4.1,27.7,10.9c3.8,3.5,6.9,7.7,9.1,12.4c2.6,5.3,4,11.3,4,17.6C40.8,104.1,22.5,122.3,0,122.3z'/>
                    <path fill='{$theme["fire"]}' d='M4.8,93.8c5.4,1.1,10.3,4.2,13.7,8.6l3.9-3c-4.1-5.3-10-9-16.6-10.4c-10.6-2.2-21.7,1.9-28.3,10.4l3.9,3 C-13.1,95.3-3.9,91.9,4.8,93.8z'/>
                    <circle fill='{$theme["fire"]}' cx='-15' cy='71' r='4.9'/>
                    <circle fill='{$theme["fire"]}' cx='15' cy='71' r='4.9'/>
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
        "/(fill|stroke|stop-color)=['\"]#([0-9a-fA-F]{4}|[0-9a-fA-F]{8})['\"]/m",
        function ($matches) {
            $attribute = $matches[1];
            $opacityAttribute = $attribute === "stop-color" ? "stop-opacity" : "{$attribute}-opacity";
            $result = convertHexColor($matches[2]);
            $color = $result["color"];
            $opacity = $result["opacity"];
            return "{$attribute}='{$color}' {$opacityAttribute}='{$opacity}'";
        },
        $svg
    );

    return $svg;
}

/**
 * Converts an SVG card to a PNG image
 *
 * @param string $svg The SVG for the card as a string
 * @param int $cardWidth The width of the card
 * @return string The generated PNG data
 */
function convertSvgToPng(string $svg, int $cardWidth, int $cardHeight): string
{
    // trim off all whitespaces to make it a valid SVG string
    $svg = trim($svg);

    // remove style and animations
    $svg = removeAnimations($svg);

    // replace newlines with spaces
    $svg = str_replace("\n", " ", $svg);

    // escape svg for shell
    $svg = escapeshellarg($svg);

    // `--pipe`: read input from pipe (stdin)
    // `--export-filename -`: write output to stdout
    // `-w 495 -h 195`: set width and height of the output image
    // `--export-type png`: set the output format to PNG
    $cmd = "echo {$svg} | inkscape --pipe --export-filename - -w {$cardWidth} -h {$cardHeight} --export-type png";

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
            // extract width from SVG
            $cardWidth = (int) preg_replace("/.*width=[\"'](\d+)px[\"'].*/", "$1", $svg);
            $cardHeight = (int) preg_replace("/.*height=[\"'](\d+)px[\"'].*/", "$1", $svg);
            $png = convertSvgToPng($svg, $cardWidth, $cardHeight);
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
