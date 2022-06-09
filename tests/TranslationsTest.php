<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

// load functions
require_once dirname(__DIR__, 1) . '/vendor/autoload.php';

final class TranslationsTest extends TestCase
{
    /**
     * Test that all locales only contain phrases appearing for the locale "en"
     * or "date_format"
     */
    public function testAllPhrasesValid(): void
    {
        $translations = include 'src/translations.php';
        $locales = array_keys($translations);
        $valid_phrases = array_merge(array_keys($translations['en']), ['date_format']);
        foreach ($locales as $locale) {
            $phrases = array_keys($translations[$locale]);
            $this->assertEquals(
                array_diff($phrases, $valid_phrases),
                [],
                "Locale $locale contains invalid phrases"
            );
        }
    }
}
