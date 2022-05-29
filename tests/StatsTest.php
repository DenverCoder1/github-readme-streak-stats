<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

// load functions
require_once dirname(__DIR__, 1) . '/vendor/autoload.php';
require_once "src/stats.php";

// load .env
$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1));
$dotenv->safeLoad();


// if environment variables are not loaded, display error
if (!isset($_SERVER["TOKEN"])) {
    $message = file_exists(dirname(__DIR__ . '../.env', 1))
        ? "Missing token in config. Check Contributing.md for details."
        : ".env was not found. Check Contributing.md for details.";

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
        // if the current streak is 0, the start date should be the same as the end date
        if ($stats["currentStreak"]["length"] == 0) {
            $this->assertEquals($stats["currentStreak"]["start"], $stats["currentStreak"]["end"]);
        }
        // test length of current streak matches time between start and end dates
        else {
            $currentStreakDelta = strtotime($stats["currentStreak"]["end"]) - strtotime($stats["currentStreak"]["start"]);
            $this->assertEquals($currentStreakDelta / 60 / 60 / 24 + 1, $stats["currentStreak"]["length"]);
        }
    }

    /**
     * Test that an invalid username returns 'not found' error
     */
    public function testInvalidUsername(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Could not find a user with that name.");
        getContributionGraphs("help");
    }

    /**
     * Test that an organization name returns 'not a user' error
     */
    public function testOrganizationName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Could not find a user with that name.");
        getContributionGraphs("DenverCoderOne");
    }

    /**
     * Test stats contributed today
     */
    public function testContributedToday(): void
    {
        $contributions = [
            "2021-04-15" => 5,
            "2021-04-16" => 3,
            "2021-04-17" => 2,
            "2021-04-18" => 7,
        ];
        $stats = getContributionStats($contributions);
        $expected = [
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
        ];
        $this->assertEquals($expected, $stats);
    }

    /**
     * Test stats missing today
     */
    public function testMissingToday(): void
    {
        $contributions = [
            "2021-04-15" => 5,
            "2021-04-16" => 3,
            "2021-04-17" => 2,
            "2021-04-18" => 0,
        ];
        $stats = getContributionStats($contributions);
        $expected = [
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
        ];
        $this->assertEquals($expected, $stats);
    }

    /**
     * Test stats missing 2 days
     */
    public function testMissingTwoDays(): void
    {
        $contributions = [
            "2021-04-15" => 5,
            "2021-04-16" => 3,
            "2021-04-17" => 0,
            "2021-04-18" => 0,
        ];
        $stats = getContributionStats($contributions);
        $expected = [
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
        ];
        $this->assertEquals($expected, $stats);
    }

    /**
     * Test multiple year streak
     */
    public function testMultipleYearStreak(): void
    {
        $contributions = [];
        for ($i = 369; $i >= 0; --$i) {
            $contributions[date('Y-m-d', strtotime("$i days ago"))] = 1;
        }
        $stats = getContributionStats($contributions);
        $expected = [
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
        ];
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
            (object) [
                "data" => (object) [
                    "user" => (object) [
                        "contributionsCollection" => (object) [
                            "contributionCalendar" => (object) [
                                "weeks" => (object) [
                                    (object) [
                                        "contributionDays" => (object) [
                                            (object) [
                                                "contributionCount" => 1,
                                                "date" => $yesterday,
                                            ],
                                            (object) [
                                                "contributionCount" => 1,
                                                "date" => $today,
                                            ],
                                            (object) [
                                                "contributionCount" => 1,
                                                "date" => $tomorrow,
                                            ],
                                            (object) [
                                                "contributionCount" => 1,
                                                "date" => $inTwoDays,
                                            ],
                                        ],
                                    ]
                                ]
                            ]
                        ],
                    ],
                ]
            ]
        ];
        $contributions = getContributionDates($contributionGraphs);
        $stats = getContributionStats($contributions);
        $expected = [
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
        ];
        $this->assertEquals($expected, $stats);
    }
}
