<?php declare (strict_types = 1);
use PHPUnit\Framework\TestCase;

// load functions
require_once "src/card.php";

final class RenderTest extends TestCase
{
    private $testTheme = array(
        "background" => "#111111",
        "border" => "#222222",
        "stroke" => "#333333",
        "ring" => "#444444",
        "fire" => "#555555",
        "currStreakNum" => "#666666",
        "sideNums" => "#777777",
        "currStreakLabel" => "#888888",
        "sideLabels" => "#999999",
        "dates" => "#000000",
    );

    private $testStats = array(
        "totalContributions" => 2048,
        "firstContribution" => "2016-08-10",
        "longestStreak" => [
            "start" => "2016-12-19",
            "end" => "2016-03-14",
            "length" => 86,
        ],
        "currentStreak" => [
            "start" => "2019-03-28",
            "end" => "2019-04-12",
            "length" => 16,
        ],
    );

    /**
     * Test normal card render
     */
    public function testCardRender(): void
    {
        // check that getRequestedTheme returns correct colors for each theme
        $render = generateCard($this->testStats, $this->testTheme);
        $expected = file_get_contents("tests/test_card.svg");
        $this->assertEquals($expected, $render);
    }

    /**
     * Test error card render
     */
    public function testErrorCardRender(): void
    {
        // check that getRequestedTheme returns correct colors for each theme
        $render = generateErrorCard("An unknown error occurred", $this->testTheme);
        $expected = file_get_contents("tests/test_error_card.svg");
        $this->assertEquals($expected, $render);
    }
}
