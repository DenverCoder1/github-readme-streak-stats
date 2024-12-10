<?php

$TRANSLATIONS = include dirname(__DIR__, 1) . "/api/translations.php";

/**
 * Get the percentage of translated phrases for each locale
 *
 * @param array $translations The translations array
 * @return array The percentage of translated phrases for each locale
 */
function getProgress(array $translations): array
{
    $phrases_to_translate = [
        "Total Contributions",
        "Current Streak",
        "Longest Streak",
        "Week Streak",
        "Longest Week Streak",
        "Present",
        "Excluding {days}",
    ];

    $translations_file = file(dirname(__DIR__, 1) . "/api/translations.php");
    $progress = [];
    foreach ($translations as $locale => $phrases) {
        // skip aliases
        if (is_string($phrases)) {
            continue;
        }
        $translated = 0;
        foreach ($phrases_to_translate as $phrase) {
            if (isset($phrases[$phrase])) {
                $translated++;
            }
        }
        $percentage = round(($translated / count($phrases_to_translate)) * 100);
        $locale_name = Locale::getDisplayName($locale, $locale);
        $line_number = getLineNumber($translations_file, $locale);
        $progress[$locale] = [
            "locale" => $locale,
            "locale_name" => $locale_name,
            "percentage" => $percentage,
            "line_number" => $line_number,
        ];
    }
    // sort by percentage
    uasort($progress, function ($a, $b) {
        return $b["percentage"] <=> $a["percentage"];
    });
    return $progress;
}

/**
 * Get the line number of the locale in the translations file
 *
 * @param array $translations_file The translations file
 * @param string $locale The locale
 * @return int The line number of the locale in the translations file
 */
function getLineNumber(array $translations_file, string $locale): int
{
    return key(preg_grep("/^\\s*\"$locale\"\\s*=>\\s*\\[/", $translations_file)) + 1;
}

/**
 * Convert progress to labeled badges
 *
 * @param array $progress The progress array
 * @return string The markdown for the image badges
 */
function progressToBadges(array $progress): string
{
    $per_row = 5;
    $table = "<table><tbody>";
    $i = 0;
    foreach (array_values($progress) as $data) {
        if ($i % $per_row === 0) {
            $table .= "<tr>";
        }
        $line_url = "https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/api/translations.php#L{$data["line_number"]}";
        $table .= "<td><a href=\"{$line_url}\"><code>{$data["locale"]}</code></a> - {$data["locale_name"]}<br /><a href=\"{$line_url}\"><img src=\"https://progress-bar.xyz/{$data["percentage"]}\" alt=\"{$data["locale_name"]} {$data["percentage"]}%\"></a></td>";
        $i++;
        if ($i % $per_row === 0) {
            $table .= "</tr>";
        }
    }
    if ($i % $per_row !== 0) {
        while ($i % $per_row !== 0) {
            $table .= "<td></td>";
            $i++;
        }
        $table .= "</tr>";
    }
    $table .= "</tbody></table>\n";
    return $table;
}

/**
 * Update readme by replacing the content between the start and end markers
 *
 * @param string $path The path to the readme file
 * @param string $start The start marker
 * @param string $end The end marker
 * @param string $content The content to replace the content between the start and end markers
 * @return int|false The number of bytes that were written to the file, or false on failure
 */
function updateReadme(string $path, string $start, string $end, string $content): int|false
{
    $readme = file_get_contents($path);
    if (strpos($readme, $start) === false || strpos($readme, $end) === false) {
        throw new Exception("Start or end marker not found in readme");
    }
    $start_pos = strpos($readme, $start) + strlen($start);
    $end_pos = strpos($readme, $end);
    $length = $end_pos - $start_pos;
    $readme = substr_replace($readme, $content, $start_pos, $length);
    return file_put_contents($path, $readme);
}

$progress = getProgress($GLOBALS["TRANSLATIONS"]);
$badges = "\n" . progressToBadges($progress);
$update = updateReadme(
    dirname(__DIR__, 1) . "/README.md",
    "<!-- TRANSLATION_PROGRESS_START -->",
    "<!-- TRANSLATION_PROGRESS_END -->",
    $badges
);
exit($update === false ? 1 : 0);
