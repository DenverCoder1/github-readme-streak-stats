<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

// load functions
require_once "src/stats.php";
require_once "src/card.php";
require_once "src/config.php";

final class StatsTest extends TestCase
{
    /**
     * Test that values seem correct for valid username
     */
    public function testValidUsername(): void
    {
        $stats = getContributionStats("DenverCoder1");
        // test total contributions
        $this->assertGreaterThan(2048, $stats["totalContributions"]);
        // test first contribution
        $this->assertEquals("2016-08-10", $stats["firstContribution"]);
        // test longest streak length
        $this->assertGreaterThanOrEqual(86, $stats["longestStreak"]["length"]);
        // test current streak length
        $this->assertGreaterThanOrEqual(0, $stats["currentStreak"]["length"]);
        // test current streak end date
        $this->assertContains($stats["currentStreak"]["end"], [date("Y-m-d"), date("Y-m-d", strtotime("yesterday"))]);
    }

    /**
     * Test that an invalid username returns 'User could not be found.' error
     */
    public function testInvalidUsername(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("User could not be found.");
        getContributionStats("help");
    }
}