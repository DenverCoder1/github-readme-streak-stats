<?php

$TRANSLATIONS = include __DIR__ . "/../src/translations.php";

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
    ];

    $progress = [];
    foreach ($translations as $locale => $phrases) {
        $translated = 0;
        foreach ($phrases_to_translate as $phrase) {
            if (isset($phrases[$phrase])) {
                $translated++;
            }
        }
        $percentage = round(($translated / count($phrases_to_translate)) * 100);
        $locale_name = Locale::getDisplayName($locale, $locale);
        $progress[$locale] = [
            "locale" => $locale,
            "locale_name" => $locale_name,
            "percentage" => $percentage,
        ];
    }
    // sort by percentage
    uasort($progress, function ($a, $b) {
        return $b["percentage"] <=> $a["percentage"];
    });
    return $progress;
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
    $badges = str_repeat("| ", $per_row) . "|" . "\n";
    $badges .= str_repeat("| --- ", $per_row) . "|" . "\n";
    $i = 0;
    foreach (array_values($progress) as $data) {
        $badges .= "| `{$data["locale"]}` - {$data["locale_name"]} <br /> ![{$data["locale_name"]} {$data["percentage"]}%](https://progress-bar.dev/{$data["percentage"]}) ";
        $i++;
        if ($i % $per_row === 0) {
            $badges .= "|\n";
        }
    }
    if ($i % $per_row !== 0) {
        $badges .= "|\n";
    }
    return $badges;
}

/**
 * Update readme by replacing the content between the start and end markers
 *
 * @param string $path The path to the readme file
 * @param string $start The start marker
 * @param string $end The end marker
 */
function updateReadme(string $path, string $start, string $end): void
{
    $readme = file_get_contents($path);
    if (strpos($readme, $start) === false || strpos($readme, $end) === false) {
        throw new Exception("Start or end marker not found in readme");
    }
    $start_pos = strpos($readme, $start) + strlen($start);
    $end_pos = strpos($readme, $end);
    $length = $end_pos - $start_pos;
    $progress = getProgress($GLOBALS["TRANSLATIONS"]);
    $badges = "\n" . progressToBadges($progress);
    $readme = substr_replace($readme, $badges, $start_pos, $length);
    file_put_contents($path, $readme);
}

updateReadme(
    __DIR__ . "/../README.md",
    "<!-- TRANSLATION_PROGRESS_START -->",
    "<!-- TRANSLATION_PROGRESS_END -->"
);
