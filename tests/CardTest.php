<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

// load functions
require_once "src/card.php";

final class CardTest extends TestCase
{
    /**
     * Test translateDays returns the input unchanged for English
     */
    public function testTranslateDaysReturnsInputForEnglish(): void
    {
        $days = ["Sun", "Sat"];
        $this->assertSame($days, translateDays($days, "en"));
    }

    /**
     * Test translateDays translates day names to Korean
     */
    public function testTranslateDaysTranslatesToKorean(): void
    {
        $this->assertSame(["일", "토"], translateDays(["Sun", "Sat"], "ko"));
    }

    /**
     * Test translateDays translates day names to Japanese
     */
    public function testTranslateDaysTranslatesToJapanese(): void
    {
        $this->assertSame(["月"], translateDays(["Mon"], "ja"));
    }

    /**
     * Test translateDays translates day names to German
     */
    public function testTranslateDaysTranslatesToGerman(): void
    {
        $this->assertSame(["Mi"], translateDays(["Wed"], "de"));
    }

    /**
     * Test getExcludingDaysText joins translated days with the default separator
     */
    public function testGetExcludingDaysTextUsesDefaultSeparator(): void
    {
        $translations = ["Excluding {days}" => "{days}를 제외하고"];
        $this->assertSame("일, 토를 제외하고", getExcludingDaysText(["Sun", "Sat"], $translations, "ko"));
    }

    /**
     * Test getExcludingDaysText uses a custom comma_separator when the locale defines one
     */
    public function testGetExcludingDaysTextUsesCustomCommaSeparator(): void
    {
        $translations = [
            "Excluding {days}" => "به جز {days}",
            "comma_separator" => "، ",
        ];
        $this->assertSame("به جز یکشنبه، شنبه", getExcludingDaysText(["Sun", "Sat"], $translations, "fa"));
    }

    /**
     * Test utf8Strlen counts ASCII characters
     */
    public function testUtf8StrlenAscii(): void
    {
        $this->assertSame(5, utf8Strlen("hello"));
        $this->assertSame(0, utf8Strlen(""));
    }

    /**
     * Test utf8Strlen counts multibyte characters as single characters
     */
    public function testUtf8StrlenMultibyte(): void
    {
        // "한글 테스트" is 6 characters (5 Korean characters and 1 space), but 16 bytes
        $this->assertSame(6, utf8Strlen("한글 테스트"));
    }

    /**
     * Test utf8WordWrap wraps text at spaces without cutting words
     */
    public function testUtf8WordWrapBreaksAtSpaces(): void
    {
        $this->assertSame("The quick\nbrown fox\njumps", utf8WordWrap("The quick brown fox jumps", 10));
    }

    /**
     * Test utf8WordWrap splits words longer than the width when cut_long_words is true
     */
    public function testUtf8WordWrapCutsLongWords(): void
    {
        $this->assertSame(
            "supercalif\nragilistic\nexpialidoc\nious",
            utf8WordWrap("supercalifragilisticexpialidocious", 10, "\n", true),
        );
    }

    /**
     * Test utf8WordWrap wraps multibyte text by character count, not byte count
     */
    public function testUtf8WordWrapMultibyte(): void
    {
        $this->assertSame(
            "짧은 단어와\n아주아주긴한국어단어가섞\n인문장\n테스트",
            utf8WordWrap("짧은 단어와 아주아주긴한국어단어가섞인문장 테스트", 12, "\n", true),
        );
    }

    /**
     * Test utf8WordWrap leaves text shorter than the width unchanged
     */
    public function testUtf8WordWrapLeavesShortTextUnchanged(): void
    {
        $this->assertSame("no spaces here", utf8WordWrap("no spaces here", 75));
    }
}
