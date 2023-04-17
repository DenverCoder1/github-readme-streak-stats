<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

// load functions
require_once dirname(__DIR__, 1) . "/vendor/autoload.php";

final class TranslationsTest extends TestCase
{
    /**
     * Test that all locales only contain phrases appearing for the locale "en" or "date_format"
     */
    public function testAllPhrasesValid(): void
    {
        $translations = include "api/translations.php";
        $locales = array_keys($translations);
        $valid_phrases = [
            "rtl",
            "date_format",
            "Total Contributions",
            "Current Streak",
            "Longest Streak",
            "Week Streak",
            "Longest Week Streak",
            "Present",
            "Excluding",
        ];
        foreach ($locales as $locale) {
            // if it is a string, assert that the alias exists in the translations file
            if (is_string($translations[$locale])) {
                $alias = $translations[$locale];
                $this->assertArrayHasKey($alias, $translations, "Alias '{$alias}' does not exist for locale '$locale'");
            }
            // otherwise, assert that all phrases are valid
            else {
                $phrases = array_keys($translations[$locale]);
                $this->assertEmpty(array_diff($phrases, $valid_phrases), "Locale '$locale' contains invalid phrases");
            }
        }
    }

    /**
     * Test that "en" is first and the remaining locales are sorted alphabetically
     */
    public function testLocalesSortedAlphabetically(): void
    {
        $translations = include "api/translations.php";
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
        $sorted = implode(", ", $sorted_locales);
        $remaining = implode(", ", $remaining_locales);
        $this->assertEquals($sorted, $remaining, "Locales are not sorted alphabetically");
    }

    /**
     * Test that all keys are normalized - lowercase language code, title case script code, uppercase region code
     */
    public function testKeysNormalized(): void
    {
        $translations = include "api/translations.php";
        $locales = array_keys($translations);
        foreach ($locales as $locale) {
            // normalize locale code
            $normalized = normalizeLocaleCode($locale);
            // check that the locale is normalized
            $this->assertEquals($locale, $normalized, "Locale '$locale' is not normalized. Expected '$normalized'.");
        }
    }

    /**
     * Test getTranslations() function
     */
    public function testGetTranslations(): void
    {
        $translations = include "api/translations.php";
        $en = $translations["en"];
        // test alias
        $this->assertEquals($translations["zh_Hans"] + $en, getTranslations("zh"), "Alias not resolved");
        // test locale not normalized with script
        $this->assertEquals($translations["zh_Hans"] + $en, getTranslations("zh-hans"), "Locale script not normalized");
        // test locale not normalized with region
        $this->assertEquals($translations["pt_BR"] + $en, getTranslations("pt-br"), "Locale region not normalized");
        // test locale region not in translations, but language is
        $this->assertEquals($translations["fr"] + $en, getTranslations("fr_XX"), "Locale missing region not resolved");
        // test locale not found
        $this->assertEquals($en, getTranslations("xx"), "Locale not found");
    }

    /**
     * Test normalizeLocaleCode() function
     */
    public function testNormalizeLocaleCode(): void
    {
        // test script not normalized
        $this->assertEquals("zh_Hans", normalizeLocaleCode("zh_hans"), "Script not normalized");
        // test region not normalized
        $this->assertEquals("pt_BR", normalizeLocaleCode("pt_br"), "Region not normalized");
        // test script and region not normalized
        $this->assertEquals("zh_Hans_CN", normalizeLocaleCode("zh_hans_cn"), "Script and region not normalized");
        // test numeric region
        $this->assertEquals("es_419", normalizeLocaleCode("es_419"), "Numeric region not normalized");
        // test dashes instead of underscores
        $this->assertEquals("zh_Hans", normalizeLocaleCode("zh-hans"), "Dashes not normalized");
        // test uppercase
        $this->assertEquals("zh_Hans", normalizeLocaleCode("ZH-HANS"), "Uppercase not normalized");
        // test invalid locale format
        $this->assertEquals("en", normalizeLocaleCode("xxxx-XXX-XXXXX"), "Invalid locale format not normalized");
    }
}
