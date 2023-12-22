<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

// load functions
require_once "api/card.php";

final class OptionsTest extends TestCase
{
    private $defaultTheme = [
        "background" => "#FFFEFE",
        "border" => "#E4E2E2",
        "stroke" => "#E4E2E2",
        "ring" => "#FB8C00",
        "fire" => "#FB8C00",
        "currStreakNum" => "#151515",
        "sideNums" => "#151515",
        "currStreakLabel" => "#FB8C00",
        "sideLabels" => "#151515",
        "dates" => "#464646",
        "excludeDaysLabel" => "#464646",
    ];

    /**
     * Test theme request parameters return colors for theme
     */
    public function testThemes(): void
    {
        // check that getRequestedTheme returns correct colors for each theme
        $themes = include "api/themes.php";
        foreach ($themes as $theme => $colors) {
            $actualColors = getRequestedTheme(["theme" => $theme]);
            $expectedColors = $colors;
            if (strpos($colors["background"], ",") !== false) {
                $expectedColors["background"] = "url(#gradient)";
                // check that the background gradient is correct
                $this->assertStringContainsString("<linearGradient", $actualColors["backgroundGradient"]);
            }
            unset($expectedColors["backgroundGradient"]);
            unset($actualColors["backgroundGradient"]);
            $this->assertEquals($expectedColors, $actualColors);
        }
    }

    /**
     * Test fallback to default theme
     */
    public function testFallbackToDefaultTheme(): void
    {
        // check that getRequestedTheme returns default for invalid theme
        // request parameters
        $params = ["theme" => "not a theme name"];
        // test that invalid theme name gives default values
        $actual = getRequestedTheme($params);
        $expected = $this->defaultTheme;
        $expected["backgroundGradient"] = "";
        $this->assertEquals($expected, $actual);
    }

    /**
     * Check that all themes have valid values for all parameters
     */
    public function testThemesHaveValidParameters(): void
    {
        // check that all themes contain all parameters and have valid values
        $themes = include "api/themes.php";
        $hexPartialRegex = "(?:[A-F0-9]{3}|[A-F0-9]{4}|[A-F0-9]{6}|[A-F0-9]{8})";
        $hexRegex = "/^#{$hexPartialRegex}$/";
        $backgroundRegex = "/^#{$hexPartialRegex}|-?\d+(?:,{$hexPartialRegex})+$/";
        foreach ($themes as $theme => $colors) {
            // check that there are no extra keys in the theme
            $this->assertEquals(
                array_diff_key($colors, $this->defaultTheme),
                [],
                "The theme '$theme' contains invalid parameters."
            );
            # check that no parameters are missing and all values are valid
            foreach (array_keys($this->defaultTheme) as $param) {
                // check that the key exists
                $this->assertArrayHasKey($param, $colors, "The theme '$theme' is missing the key '$param'.");
                if ($param === "background") {
                    // check that the key is a valid background value
                    $this->assertMatchesRegularExpression(
                        $backgroundRegex,
                        $colors[$param],
                        "The parameter '$param' of '$theme' is not a valid background value."
                    );
                    continue;
                }
                // check that the key is a valid hex color
                $this->assertMatchesRegularExpression(
                    $hexRegex,
                    strtoupper($colors[$param]),
                    "The parameter '$param' of '$theme' is not a valid hex color."
                );
                // check that the key is a valid hex color in uppercase
                $this->assertMatchesRegularExpression(
                    $hexRegex,
                    $colors[$param],
                    "The parameter '$param' of '$theme' should not contain lowercase letters."
                );
            }
        }
    }

    /**
     * Test parameters to override specific color
     */
    public function testColorOverrideParameters(): void
    {
        // clear request parameters
        $params = [];
        // set default expected value
        $expected = $this->defaultTheme;
        foreach (array_keys($this->defaultTheme) as $param) {
            // set request parameter
            $params[$param] = "f00";
            // update parameter in expected result
            $expected = array_merge($expected, [$param => "#f00"]);
            // test color change
            $actual = getRequestedTheme($params);
            $expected["backgroundGradient"] = "";
            $this->assertEquals($expected, $actual);
        }
    }

    /**
     * Test color override parameters - all valid color inputs
     */
    public function testValidColorInputs(): void
    {
        // valid color inputs and what the output color will be
        $validInputTypes = [
            "f00" => "#f00",
            "f00f" => "#f00f",
            "ff0000" => "#ff0000",
            "FF0000" => "#ff0000",
            "ff0000ff" => "#ff0000ff",
            "red" => "red",
        ];
        // set default expected value
        $expected = $this->defaultTheme;
        foreach ($validInputTypes as $input => $output) {
            // set request parameter
            $params = ["background" => $input];
            // update parameter in expected result
            $expected = array_merge($expected, ["background" => $output]);
            // test color change
            $actual = getRequestedTheme($params);
            $expected["backgroundGradient"] = "";
            $this->assertEquals($expected, $actual);
        }
    }

    /**
     * Test color override parameters - invalid color inputs
     */
    public function testInvalidColorInputs(): void
    {
        // invalid color inputs
        $invalidInputTypes = [
            "g00", # not 0-9, A-F
            "f00f0", # invalid number of characters
            "fakecolor", # invalid color name
        ];
        foreach ($invalidInputTypes as $input) {
            // set request parameter
            $params = ["background" => $input];
            // test that theme is still default
            $actual = getRequestedTheme($params);
            $expected = $this->defaultTheme;
            $expected["backgroundGradient"] = "";
            $this->assertEquals($expected, $actual);
        }
    }

    /**
     * Test hide_border parameter
     */
    public function testHideBorder(): void
    {
        // check that getRequestedTheme returns transparent border when hide_border is true
        $params = ["hide_border" => "true"];
        $theme = getRequestedTheme($params);
        $this->assertEquals("#0000", $theme["border"]);
        // check that getRequestedTheme returns solid border when hide_border is not true
        $params = ["hide_border" => "false"];
        $theme = getRequestedTheme($params);
        $this->assertEquals($this->defaultTheme["border"], $theme["border"]);
    }

    /**
     * Test date formatter for same year
     */
    public function testDateFormatSameYear(): void
    {
        $year = date("Y");
        $formatted = formatDate("$year-04-12", "M j[, Y]", "en");
        $this->assertEquals("Apr 12", $formatted);
    }

    /**
     * Test date formatter for different year
     */
    public function testDateFormatDifferentYear(): void
    {
        $formatted = formatDate("2000-04-12", "M j[, Y]", "en");
        $this->assertEquals("Apr 12, 2000", $formatted);
    }

    /**
     * Test date formatter no brackets different year
     */
    public function testDateFormatNoBracketsDiffYear(): void
    {
        $formatted = formatDate("2000-04-12", "Y/m/d", "en");
        $this->assertEquals("2000/04/12", $formatted);
    }

    /**
     * Test date formatter no brackets same year
     */
    public function testDateFormatNoBracketsSameYear(): void
    {
        $year = date("Y");
        $formatted = formatDate("$year-04-12", "Y/m/d", "en");
        $this->assertEquals("$year/04/12", $formatted);
    }

    /**
     * Test normalizing theme name
     */
    public function testNormalizeThemeName(): void
    {
        $this->assertEquals("mytheme", normalizeThemeName("myTheme"));
        $this->assertEquals("my-theme", normalizeThemeName("My_Theme"));
        $this->assertEquals("my-theme", normalizeThemeName("my_theme"));
        $this->assertEquals("my-theme", normalizeThemeName("my-theme"));
    }

    /**
     * Test all theme names are normalized
     */
    public function testAllThemeNamesNormalized(): void
    {
        $themes = include "src/themes.php";
        foreach (array_keys($themes) as $theme) {
            $normalized = normalizeThemeName($theme);
            $this->assertEquals(
                $theme,
                $normalized,
                "Theme name '$theme' is not normalized. It should contain only lowercase letters, numbers, and dashes. Consider renaming it to '$normalized'."
            );
        }
    }
}
