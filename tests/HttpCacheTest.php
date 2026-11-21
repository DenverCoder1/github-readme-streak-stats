<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__, 1) . "/vendor/autoload.php";
require_once "src/stats.php";
require_once "src/cache.php";
require_once "src/generator.php";

final class HttpCacheTest extends TestCase
{
    /**
     * Missing stats (error path) should use the short HTTP cache lifetime.
     */
    public function testNullStatsUseShortCache(): void
    {
        $this->assertSame(SHORT_CACHE_DURATION, getHttpCacheSeconds(null));
    }

    /**
     * Streak length 0 is treated as stale by statsMissingOrStale → short HTTP cache.
     */
    public function testZeroStreakUsesShortCache(): void
    {
        $stats = [
            "mode" => "daily",
            "currentStreak" => [
                "start" => "2024-01-01",
                "end" => "2024-01-01",
                "length" => 0,
            ],
        ];
        $this->assertSame(SHORT_CACHE_DURATION, getHttpCacheSeconds($stats));
    }

    /**
     * Streak that ended before today is stale → short HTTP cache.
     */
    public function testStaleStreakUsesShortCache(): void
    {
        $today = getCurrentDate("UTC");
        $yesterday = (new DateTimeImmutable($today, new DateTimeZone("UTC")))
            ->modify("-1 day")
            ->format("Y-m-d");
        $stats = [
            "mode" => "daily",
            "currentStreak" => [
                "start" => $yesterday,
                "end" => $yesterday,
                "length" => 1,
            ],
        ];
        $this->assertSame(SHORT_CACHE_DURATION, getHttpCacheSeconds($stats, "UTC"));
    }

    /**
     * Fresh daily streak (includes today) caches until next midnight, capped at 24h.
     */
    public function testFreshStreakCachesUntilMidnight(): void
    {
        $today = getCurrentDate("UTC");
        $stats = [
            "mode" => "daily",
            "currentStreak" => [
                "start" => $today,
                "end" => $today,
                "length" => 1,
            ],
        ];

        $now = new DateTimeImmutable("2026-10-08 11:47:00", new DateTimeZone("UTC"));
        $seconds = getHttpCacheSeconds($stats, "UTC", $now);
        $expected = getSecondsUntilNextMidnight("UTC", $now);

        $this->assertSame($expected, $seconds);
        $this->assertLessThanOrEqual(CACHE_DURATION, $seconds);
        $this->assertGreaterThan(0, $seconds);
        // 11:47 UTC → seconds until midnight = 12h 13m = 43980
        $this->assertSame(12 * 3600 + 13 * 60, $seconds);
    }

    /**
     * Fresh streak max-age never exceeds CACHE_DURATION even early in the day.
     */
    public function testFreshStreakCappedAtCacheDuration(): void
    {
        $today = getCurrentDate("UTC");
        $stats = [
            "mode" => "daily",
            "currentStreak" => [
                "start" => $today,
                "end" => $today,
                "length" => 3,
            ],
        ];

        $now = new DateTimeImmutable("2026-10-08 00:00:01", new DateTimeZone("UTC"));
        $seconds = getHttpCacheSeconds($stats, "UTC", $now);
        $this->assertSame(min(CACHE_DURATION, getSecondsUntilNextMidnight("UTC", $now)), $seconds);
        $this->assertLessThanOrEqual(CACHE_DURATION, $seconds);
    }

    /**
     * getSecondsUntilNextMidnight respects the requested timezone.
     */
    public function testSecondsUntilMidnightUsesTimezone(): void
    {
        $now = new DateTimeImmutable("2026-10-08 15:30:00", new DateTimeZone("America/New_York"));
        $seconds = getSecondsUntilNextMidnight("America/New_York", $now);
        // 15:30 → midnight = 8h 30m = 30600
        $this->assertSame(8 * 3600 + 30 * 60, $seconds);
    }
}
