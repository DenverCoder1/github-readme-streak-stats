<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

// load functions
require_once dirname(__DIR__, 1) . '/vendor/autoload.php';

final class TranslationsTest extends TestCase
{
    /**
     * Test that all locales only contain phrases appearing for the locale "en" or "date_format"
     */
    public function testAllPhrasesValid(): void
    {
        $translations = include 'src/translations.php';
        $locales = array_keys($translations);
        $valid_phrases = ["date_format", "Total Contributions", "Current Streak", "Longest Streak", "Present"];
        foreach ($locales as $locale) {
            $phrases = array_keys($translations[$locale]);
            $this->assertEmpty(array_diff($phrases, $valid_phrases), "Locale $locale contains invalid phrases");
        }
    }

    /**
     * Test that "en" is first and the remaining locales are sorted alphabetically
     */
    public function testLocalesSortedAlphabetically(): void
    {
        $translations = include 'src/translations.php';
        $locales = array_keys($translations);
        // check that "en" is first
        $this->assertEquals("en", $locales[0]);
        // check that the remaining locales are sorted alphabetically
        $remaining_locales = array_slice($locales, 1);
        $sorted_locales = call_user_func(function (array $arr) {
            asort($arr);
            return $arr;
        }, $remaining_locales);
        // check that the remaining locales are sorted alphabetically
        $this->assertEquals(implode(', ', $sorted_locales), implode(', ', $remaining_locales));
    }
}
