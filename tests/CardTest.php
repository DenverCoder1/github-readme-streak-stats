<?php declare (strict_types = 1);
use PHPUnit\Framework\TestCase;

// load functions
require_once "src/card.php";

final class CardTest extends TestCase
{
    private $default_theme = Array(
        "background" => "#fffefe",
        "border" => "#e4e2e2",
        "stroke" => "#e4e2e2",
        "ring" => "#fb8c00",
        "fire" => "#fb8c00",
        "currStreakNum" => "#151515",
        "sideNums" => "#151515",
        "currStreakLabel" => "#fb8c00",
        "sideLabels" => "#151515",
        "dates" => "#464646",
    );

    /**
     * Test theme parameters
     */
    public function testThemes(): void
    {
        // check that getRequestedTheme returns correct colors for each theme
        $themes = include "src/themes.php";
        foreach ($themes as $theme => $colors) {
            $_REQUEST = ["theme" => $theme];
            $actualColors = getRequestedTheme();
            $this->assertEquals($colors, $actualColors);
        }
    }

    /**
     * Test fallback to default theme
     */
    public function testFallbackToDefaultTheme(): void
    {
        // check that getRequestedTheme returns default for invalid theme
        // request parameters
        $_REQUEST = ["theme" => "not a theme name"];
        // test that invalid theme name gives default values
        $this->assertEquals($this->default_theme, getRequestedTheme());
    }

    /**
     * Test parameters to override specific color
     */
    public function testColorOverrideParameters(): void
    {
        // check that getRequestedTheme returns default for invalid theme
        // clear request parameters
        $_REQUEST = [];
        // set default expected value
        $expected = $this->default_theme;
        foreach (array_keys($this->default_theme) as $param) {
            // set request parameter
            $_REQUEST[$param] = "f00";
            // update parameter in expected result
            $expected = array_merge(
                $expected,
                [$param => "#f00"],
            );
            // test color change
            $this->assertEquals($expected, getRequestedTheme());
        }
    }

    /**
     * Test hide_border parameter
     */
    public function testHideBorder(): void
    {
        // check that getRequestedTheme returns transparent border when hide_border is true
        $_REQUEST = ["hide_border" => "true"];
        $theme = getRequestedTheme();
        $this->assertEquals("#0000", $theme["border"]);
        // check that getRequestedTheme returns solid border when hide_border is not true
        $_REQUEST = ["hide_border" => "false"];
        $theme = getRequestedTheme();
        $this->assertEquals("#e4e2e2", $theme["border"]);
    }

    /**
     * Test date formatter for same year
     */
    public function testDateFormatSameYear(): void
    {
        $year = date("Y");
        $formatted = formatDate("$year-04-12");
        $this->assertEquals("Apr 12", $formatted);
    }

    /**
     * Test date formatter for different year
     */
    public function testDateFormatDifferentYear(): void
    {
        $formatted = formatDate("2000-04-12");
        $this->assertEquals("Apr 12, 2000", $formatted);
    }
}
