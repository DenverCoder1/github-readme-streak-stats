<?php declare (strict_types = 1);
use PHPUnit\Framework\TestCase;

// load functions
require_once "src/stats.php";

// load config if the file exists
if (file_exists("src/config.php")) {
    require_once "src/config.php";
}
// if environment variables are not loaded, display error
if (!getenv("TOKEN") || !getenv("USERNAME")) {
    $message = file_exists("config.php")
    ? "Missing token or username in config. Check Contributing.md for details."
    : "src/config.php was not found. Check Contributing.md for details.";
    die($message);
}

final class StatsTest extends TestCase
{
    /**
     * Test that values seem correct for valid username
     */
    public function testValidUsername(): void
    {
        $contributionGraphs = getContributionGraphs("DenverCoder1");
        $contributions = getContributionDates($contributionGraphs);
        $stats = getContributionStats($contributions);
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
        $this->assertContains($stats["currentStreak"]["end"], [date("Y-m-d"), date("Y-m-d", strtotime("yesterday")), date("Y-m-d", strtotime("tomorrow"))]);
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
        getContributionGraphs("help");
    }

    /**
     * Test that an organization name returns 'not a user' error
     */
    public function testOrganizationName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The username given is not a user.");
        getContributionGraphs("DenverCoderOne");
    }

    /**
     * Test stats contributed today
     */
    public function testContributedToday(): void
    {
        $contributions = array(
            "2021-04-15" => 5,
            "2021-04-16" => 3,
            "2021-04-17" => 2,
            "2021-04-18" => 7,
        );
        $stats = getContributionStats($contributions);
        $expected = array(
            "totalContributions" => 17,
            "firstContribution" => "2021-04-15",
            "longestStreak" => [
                "start" => "2021-04-15",
                "end" => "2021-04-18",
                "length" => 4,
            ],
            "currentStreak" => [
                "start" => "2021-04-15",
                "end" => "2021-04-18",
                "length" => 4,
            ],
        );
        $this->assertEquals($expected, $stats);
    }

    /**
     * Test stats missing today
     */
    public function testMissingToday(): void
    {
        $contributions = array(
            "2021-04-15" => 5,
            "2021-04-16" => 3,
            "2021-04-17" => 2,
            "2021-04-18" => 0,
        );
        $stats = getContributionStats($contributions);
        $expected = array(
            "totalContributions" => 10,
            "firstContribution" => "2021-04-15",
            "longestStreak" => [
                "start" => "2021-04-15",
                "end" => "2021-04-17",
                "length" => 3,
            ],
            "currentStreak" => [
                "start" => "2021-04-15",
                "end" => "2021-04-17",
                "length" => 3,
            ],
        );
        $this->assertEquals($expected, $stats);
    }

    /**
     * Test stats missing 2 days
     */
    public function testMissingTwoDays(): void
    {
        $contributions = array(
            "2021-04-15" => 5,
            "2021-04-16" => 3,
            "2021-04-17" => 0,
            "2021-04-18" => 0,
        );
        $stats = getContributionStats($contributions);
        $expected = array(
            "totalContributions" => 8,
            "firstContribution" => "2021-04-15",
            "longestStreak" => [
                "start" => "2021-04-15",
                "end" => "2021-04-16",
                "length" => 2,
            ],
            "currentStreak" => [
                "start" => "2021-04-18",
                "end" => "2021-04-18",
                "length" => 0,
            ],
        );
        $this->assertEquals($expected, $stats);
    }

    /**
     * Test multiple year streak
     */
    public function testMultipleYearStreak(): void
    {
        $contributions = array();
        for ($i = 369; $i >= 0; --$i) {
            $contributions[date('Y-m-d', strtotime("$i days ago"))] = 1;
        }
        $stats = getContributionStats($contributions);
        $expected = array(
            "totalContributions" => 370,
            "firstContribution" => date('Y-m-d', strtotime('369 days ago')),
            "longestStreak" => [
                "start" => date('Y-m-d', strtotime('369 days ago')),
                "end" => date('Y-m-d'),
                "length" => 370,
            ],
            "currentStreak" => [
                "start" => date('Y-m-d', strtotime('369 days ago')),
                "end" => date('Y-m-d'),
                "length" => 370,
            ],
        );
        $this->assertEquals($expected, $stats);
    }

    /**
     * Test future commits
     * Tomorrow should count because of timezone differences, but further ahead should not
     */
    public function testFutureCommits(): void
    {
        $yesterday = date('Y-m-d', strtotime('yesterday'));
        $today = date('Y-m-d', strtotime('today'));
        $tomorrow = date('Y-m-d', strtotime('tomorrow'));
        $inTwoDays = date('Y-m-d', strtotime("$today +2 days"));
        $contributionGraphs = [
            "<rect width=\"10\" height=\"10\" x=\"-2\" y=\"13\" class=\"ContributionCalendar-day\" rx=\"2\" ry=\"2\" data-count=\"1\" data-date=\"$yesterday\" data-level=\"1\"></rect>
            <rect width=\"10\" height=\"10\" x=\"-2\" y=\"26\" class=\"ContributionCalendar-day\" rx=\"2\" ry=\"2\" data-count=\"1\" data-date=\"$today\" data-level=\"2\"></rect>
            <rect width=\"10\" height=\"10\" x=\"-2\" y=\"39\" class=\"ContributionCalendar-day\" rx=\"2\" ry=\"2\" data-count=\"1\" data-date=\"$tomorrow\" data-level=\"0\"></rect>
            <rect width=\"10\" height=\"10\" x=\"-2\" y=\"52\" class=\"ContributionCalendar-day\" rx=\"2\" ry=\"2\" data-count=\"1\" data-date=\"$inTwoDays\" data-level=\"0\"></rect>",
        ];
        $contributions = getContributionDates($contributionGraphs);
        $stats = getContributionStats($contributions);
        $expected = array(
            "totalContributions" => 3,
            "firstContribution" => date('Y-m-d', strtotime('yesterday')),
            "longestStreak" => [
                "start" => date('Y-m-d', strtotime('yesterday')),
                "end" => date('Y-m-d', strtotime('tomorrow')),
                "length" => 3,
            ],
            "currentStreak" => [
                "start" => date('Y-m-d', strtotime('yesterday')),
                "end" => date('Y-m-d', strtotime('tomorrow')),
                "length" => 3,
            ],
        );
        $this->assertEquals($expected, $stats);
    }
}
