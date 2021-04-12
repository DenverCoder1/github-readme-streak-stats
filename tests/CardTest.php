<?php declare (strict_types = 1);
use PHPUnit\Framework\TestCase;

// load functions
require_once "src/card.php";

final class CardTest extends TestCase
{
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
        $themes = include "src/themes.php";
        $_REQUEST = ["theme" => "not a theme name"];
        $actualColors = getRequestedTheme();
        $this->assertEquals($themes["default"], $actualColors);
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
