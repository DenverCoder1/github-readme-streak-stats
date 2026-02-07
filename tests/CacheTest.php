<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

// load functions
require_once dirname(__DIR__, 1) . "/vendor/autoload.php";
require_once "src/cache.php";

final class CacheTest extends TestCase
{
    /**
     * Test cache key generation produces consistent results
     */
    public function testCacheKeyConsistency(): void
    {
        $key1 = getCacheKey("testuser", ["mode" => "weekly"]);
        $key2 = getCacheKey("testuser", ["mode" => "weekly"]);
        $this->assertEquals($key1, $key2, "Same inputs should produce same cache key");
    }

    /**
     * Test cache key generation produces different results for different inputs
     */
    public function testCacheKeyDifferentInputs(): void
    {
        $key1 = getCacheKey("user1", []);
        $key2 = getCacheKey("user2", []);
        $this->assertNotEquals($key1, $key2, "Different users should produce different cache keys");

        $key3 = getCacheKey("testuser", ["mode" => "weekly"]);
        $key4 = getCacheKey("testuser", ["mode" => "daily"]);
        $this->assertNotEquals($key3, $key4, "Different options should produce different cache keys");
    }

    /**
     * Test that cache key prevents hash collisions
     * e.g., user "ab" with options containing "cd" should not collide with user "abc" with options containing "d"
     */
    public function testCacheKeyNoCollisions(): void
    {
        // This tests the fix for the hash collision vulnerability
        $key1 = getCacheKey("ab", ["option" => "cd"]);
        $key2 = getCacheKey("abc", ["option" => "d"]);
        $this->assertNotEquals($key1, $key2, "Similar concatenated strings should not produce same hash");

        $key3 = getCacheKey("ab", ["x" => "cd"]);
        $key4 = getCacheKey("abcd", []);
        $this->assertNotEquals($key3, $key4, "User + options should not collide with user alone");
    }

    /**
     * Test cache key generation sorts options for consistency
     */
    public function testCacheKeyOptionOrdering(): void
    {
        $key1 = getCacheKey("testuser", ["a" => "1", "b" => "2"]);
        $key2 = getCacheKey("testuser", ["b" => "2", "a" => "1"]);
        $this->assertEquals($key1, $key2, "Option order should not affect cache key");
    }

    /**
     * Test cache key is filename-safe (SHA256 hex)
     */
    public function testCacheKeyFormat(): void
    {
        $key = getCacheKey("testuser", ["mode" => "weekly"]);
        $this->assertMatchesRegularExpression("/^[a-f0-9]{64}$/", $key, "Cache key should be 64-character hex string");
    }

    /**
     * Test cache file path generation
     */
    public function testGetCacheFilePath(): void
    {
        $key = "abc123";
        $path = getCacheFilePath($key);
        $this->assertStringEndsWith("/cache/abc123.json", $path);
    }

    /**
     * Test setCachedStats and getCachedStats roundtrip
     */
    public function testCacheRoundtrip(): void
    {
        $user = "roundtripuser";
        $options = ["mode" => "weekly", "starting_year" => 2020];
        $stats = [
            "totalContributions" => 100,
            "currentStreak" => ["start" => "2024-01-01", "end" => "2024-01-10", "length" => 10],
            "longestStreak" => ["start" => "2023-06-01", "end" => "2023-07-15", "length" => 45],
            "firstContribution" => "2020-01-15",
        ];

        // Write to cache
        $result = setCachedStats($user, $options, $stats);
        $this->assertTrue($result, "setCachedStats should return true on success");

        // Read back from cache
        $cached = getCachedStats($user, $options);
        $this->assertNotNull($cached, "getCachedStats should return cached data");
        $this->assertEquals($stats, $cached, "Cached data should match original");
    }

    /**
     * Test getCachedStats returns null for non-existent cache
     */
    public function testGetCachedStatsNotFound(): void
    {
        $result = getCachedStats("nonexistentuser12345", []);
        $this->assertNull($result, "getCachedStats should return null for non-existent cache");
    }

    /**
     * Test setCachedStats handles invalid data gracefully
     */
    public function testSetCachedStatsWithEmptyStats(): void
    {
        $result = setCachedStats("emptyuser", [], []);
        $this->assertTrue($result, "setCachedStats should handle empty stats array");

        $cached = getCachedStats("emptyuser", []);
        $this->assertEquals([], $cached, "Empty stats should be cached and retrieved");
    }

    /**
     * Test clearUserCache clears cache for user with default options
     */
    public function testClearUserCache(): void
    {
        $user = "clearableuser";
        $stats = ["totalContributions" => 50];

        // Set cache
        setCachedStats($user, [], $stats);
        $this->assertNotNull(getCachedStats($user, []));

        // Clear cache
        $result = clearUserCache($user);
        $this->assertTrue($result);

        // Verify cleared
        $this->assertNull(getCachedStats($user, []));
    }

    /**
     * Test clearUserCache returns true for non-existent user
     */
    public function testClearUserCacheNonExistent(): void
    {
        $result = clearUserCache("definitelynotauser999");
        $this->assertTrue($result, "clearUserCache should return true for non-existent cache");
    }

    /**
     * Test ensureCacheDir creates directory
     */
    public function testEnsureCacheDir(): void
    {
        $result = ensureCacheDir();
        $this->assertTrue($result);
        $this->assertTrue(is_dir(CACHE_DIR));
    }
}
