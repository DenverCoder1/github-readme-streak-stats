<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

// load functions
require_once "src/stats.php";
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
        $this->assertIsInt($stats["totalContributions"]);
        $this->assertGreaterThan(2300, $stats["totalContributions"]);
        // test first contribution
        $this->assertEquals("2016-08-10", $stats["firstContribution"]);
        // test longest streak length
        $this->assertIsInt($stats["longestStreak"]["length"]);
        $this->assertGreaterThanOrEqual(98, $stats["longestStreak"]["length"]);
        // test current streak length
        $this->assertIsInt($stats["currentStreak"]["length"]);
        $this->assertGreaterThanOrEqual(0, $stats["currentStreak"]["length"]);
        // test longest streak start date are in form YYYY-MM-DD
        $this->assertMatchesRegularExpression("/2\d{3}-[01]\d-[0-3]\d/", $stats["longestStreak"]["start"]);
        // test longest streak end date are in form YYYY-MM-DD
        $this->assertMatchesRegularExpression("/2\d{3}-[01]\d-[0-3]\d/", $stats["longestStreak"]["end"]);
        // test current streak start date are in form YYYY-MM-DD
        $this->assertMatchesRegularExpression("/2\d{3}-[01]\d-[0-3]\d/", $stats["currentStreak"]["start"]);
        // test current streak end date are in form YYYY-MM-DD
        $this->assertMatchesRegularExpression("/2\d{3}-[01]\d-[0-3]\d/", $stats["currentStreak"]["end"]);
        // test current streak end date is today or yesterday
        $this->assertContains($stats["currentStreak"]["end"], [date("Y-m-d"), date("Y-m-d", strtotime("yesterday"))]);
        // test length of longest streak matches time between start and end dates
        $longestStreakDelta = strtotime($stats["longestStreak"]["end"]) - strtotime($stats["longestStreak"]["start"]);
        $this->assertEquals($longestStreakDelta / 60 / 60 / 24 + 1, $stats["longestStreak"]["length"]);
        // test length of current streak matches time between start and end dates
        $currentStreakDelta = strtotime($stats["currentStreak"]["end"]) - strtotime($stats["currentStreak"]["start"]);
        $this->assertEquals($currentStreakDelta / 60 / 60 / 24 + 1, $stats["currentStreak"]["length"]);
    }

    /**
     * Test that an invalid username returns 'not found' error
     */
    public function testInvalidUsername(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("User could not be found.");
        getContributionStats("help");
    }

    /**
     * Test that an organization name returns 'not a user' error
     */
    public function testOrganizationName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The username given is not a user.");
        getContributionStats("DenverCoderOne");
    }
}