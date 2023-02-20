<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

// load functions
require_once "src/card.php";

final class RenderTest extends TestCase
{
    private $testParams = [
        "background" => "000000",
        "border" => "111111",
        "stroke" => "222222",
        "ring" => "333333",
        "fire" => "444444",
        "currStreakNum" => "555555",
        "sideNums" => "666666",
        "currStreakLabel" => "777777",
        "sideLabels" => "888888",
        "dates" => "999999",
    ];

    private $testStats = [
        "mode" => "daily",
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
    ];

    /**
     * Test normal card render
     */
    public function testCardRender(): void
    {
        // Check that the card is rendered as expected
        $render = generateCard($this->testStats, $this->testParams);
        $expected = file_get_contents("tests/expected/test_card.svg");
        $this->assertEquals($expected, $render);
    }

    /**
     * Test error card render
     */
    public function testErrorCardRender(): void
    {
        // Check that error card is returned when no stats are provided
        $render = generateErrorCard("An unknown error occurred", $this->testParams);
        $expected = file_get_contents("tests/expected/test_error_card.svg");
        $this->assertEquals($expected, $render);
    }

    /**
     * Test date_format parameter in render
     */
    public function testDateFormatRender(): void
    {
        $year = date("Y");
        $this->testStats["currentStreak"]["end"] = "$year-04-12";
        $this->testParams["date_format"] = "[Y-]m-d";
        // Check that the card is rendered as expected
        $render = generateCard($this->testStats, $this->testParams);
        $this->assertStringContainsString("2016-08-10 - Present", $render);
        $this->assertStringContainsString("2019-03-28 - 04-12", $render);
        $this->assertStringContainsString("2016-12-19 - 2016-03-14", $render);
    }

    /**
     * Test locale parameter in render with date_format in translation file
     */
    public function testLocaleRenderDateFormat(): void
    {
        $this->testParams["locale"] = "ja";
        // Check that the card is rendered as expected
        $render = generateCard($this->testStats, $this->testParams);
        $this->assertStringContainsString("2,048", $render);
        $this->assertStringContainsString("総ｺﾝﾄﾘﾋﾞｭｰｼｮﾝ数", $render);
        $this->assertStringContainsString("2016.8.10 - 今", $render);
        $this->assertStringContainsString("16", $render);
        $this->assertStringContainsString("現在のストリーク", $render);
        $this->assertStringContainsString("2019.3.28 - 2019.4.12", $render);
        $this->assertStringContainsString("86", $render);
        $this->assertStringContainsString("最長のストリーク", $render);
        $this->assertStringContainsString("2016.12.19 - 2016.3.14", $render);
    }

    /**
     * Test border radius
     */
    public function testBorderRadius(): void
    {
        $this->testParams["border_radius"] = "16";
        // Check that the card is rendered as expected
        $render = generateCard($this->testStats, $this->testParams);
        $this->assertStringContainsString("<rect width='495' height='195' rx='16'/>", $render);
        $this->assertStringContainsString(
            "<rect stroke='#111111' fill='#000000' rx='16' x='0.5' y='0.5' width='494' height='194'/>",
            $render
        );
    }

    /**
     * Test split lines function
     */
    public function testSplitLines(): void
    {
        // Check normal label, no split
        $this->assertEquals("Total Contributions", splitLines("Total Contributions", 24, -9));
        // Check label that is too long, split
        $this->assertEquals(
            "<tspan x='81.5' dy='-9'>Chuỗi đóng góp hiện</tspan><tspan x='81.5' dy='16'>tại</tspan>",
            splitLines("Chuỗi đóng góp hiện tại", 22, -9)
        );
        // Check label with manually inserted line break, split
        $this->assertEquals(
            "<tspan x='81.5' dy='-9'>Chuỗi đóng góp</tspan><tspan x='81.5' dy='16'>hiện tại</tspan>",
            splitLines("Chuỗi đóng góp\nhiện tại", 22, -9)
        );
        // Check date range label, no split
        $this->assertEquals("Mar 28, 2019 – Apr 12, 2019", splitLines("Mar 28, 2019 – Apr 12, 2019", 28, 0));
        // Check date range label that is too long, split
        $this->assertEquals(
            "<tspan x='81.5' dy='0'>19 de dez. de 2021</tspan><tspan x='81.5' dy='16'>- 14 de mar.</tspan>",
            splitLines("19 de dez. de 2021 - 14 de mar.", 24, 0)
        );
    }

    /**
     * Test disable_animations parameter
     */
    public function testDisableAnimations(): void
    {
        $this->testParams["disable_animations"] = "true";
        // Check that the card is rendered as expected
        $response = generateOutput($this->testStats, $this->testParams);
        $render = $response["body"];
        $this->assertStringNotContainsString("opacity: 0;", $render);
        $this->assertStringContainsString("opacity: 1;", $render);
        $this->assertStringContainsString("font-size: 28px;", $render);
        $this->assertStringNotContainsString("animation:", $render);
        $this->assertStringNotContainsString("<style>", $render);
    }

    /**
     * Test alpha in hex colors
     */
    public function testAlphaInHexColors(): void
    {
        // "tranparent" gets converted to "#0000"
        $this->testParams["background"] = "transparent";
        $render = generateOutput($this->testStats, $this->testParams)["body"];
        $this->assertStringContainsString("fill='#000000' fill-opacity='0'", $render);

        // "#ff000080" gets converted to "#ff0000" and fill-opacity is set to 0.50196078431373
        $this->testParams["background"] = "ff000080";
        $render = generateOutput($this->testStats, $this->testParams)["body"];
        $this->assertStringContainsString("fill='#ff0000' fill-opacity='0.50196078431373'", $render);

        // "#ff0000" gets converted to "#ff0000" and fill-opacity is not set
        $this->testParams["background"] = "ff0000ff";
        $render = generateOutput($this->testStats, $this->testParams)["body"];
        $this->assertStringContainsString("fill='#ff0000' fill-opacity='1'", $render);

        // test stroke opacity
        $this->testParams["border"] = "00ff0080";
        $render = generateOutput($this->testStats, $this->testParams)["body"];
        $this->assertStringContainsString("stroke='#00ff00' stroke-opacity='0.50196078431373'", $render);
    }
}
