<?php

declare(strict_types=1);

/**
 * Simple file-based cache for GitHub contribution stats
 *
 * Caches stats for 24 hours to avoid repeated API calls
 */

// Default cache duration: 24 hours (in seconds)
define('CACHE_DURATION', 24 * 60 * 60);
define('CACHE_DIR', __DIR__ . '/../cache');

/**
 * Generate a cache key for a user's request
 *
 * @param string $user GitHub username
 * @param array $options Additional options that affect the stats (mode, exclude_days, starting_year)
 * @return string Cache key (filename-safe)
 */
function getCacheKey(string $user, array $options = []): string
{
    // Normalize options
    ksort($options);
    $optionsString = json_encode($options);
    return md5($user . $optionsString);
}

/**
 * Get the cache file path for a given key
 *
 * @param string $key Cache key
 * @return string Full path to cache file
 */
function getCacheFilePath(string $key): string
{
    return CACHE_DIR . '/' . $key . '.json';
}

/**
 * Ensure the cache directory exists
 *
 * @return bool True if directory exists or was created
 */
function ensureCacheDir(): bool
{
    if (!is_dir(CACHE_DIR)) {
        return mkdir(CACHE_DIR, 0755, true);
    }
    return true;
}

/**
 * Get cached stats if available and not expired
 *
 * @param string $user GitHub username
 * @param array $options Additional options
 * @param int $maxAge Maximum age in seconds (default: 24 hours)
 * @return array|null Cached stats array or null if not cached/expired
 */
function getCachedStats(string $user, array $options = [], int $maxAge = CACHE_DURATION): ?array
{
    $key = getCacheKey($user, $options);
    $filePath = getCacheFilePath($key);

    if (!file_exists($filePath)) {
        return null;
    }

    $fileAge = time() - filemtime($filePath);
    if ($fileAge > $maxAge) {
        // Cache expired, delete the file
        @unlink($filePath);
        return null;
    }

    $contents = @file_get_contents($filePath);
    if ($contents === false) {
        return null;
    }

    $data = json_decode($contents, true);
    if (!is_array($data)) {
        return null;
    }

    return $data;
}

/**
 * Save stats to cache
 *
 * @param string $user GitHub username
 * @param array $options Additional options
 * @param array $stats Stats array to cache
 * @return bool True if successfully cached
 */
function setCachedStats(string $user, array $options, array $stats): bool
{
    if (!ensureCacheDir()) {
        return false;
    }

    $key = getCacheKey($user, $options);
    $filePath = getCacheFilePath($key);

    $data = json_encode($stats, JSON_PRETTY_PRINT);
    if ($data === false) {
        return false;
    }

    return file_put_contents($filePath, $data, LOCK_EX) !== false;
}

/**
 * Clear all expired cache files
 *
 * @param int $maxAge Maximum age in seconds
 * @return int Number of files deleted
 */
function clearExpiredCache(int $maxAge = CACHE_DURATION): int
{
    if (!is_dir(CACHE_DIR)) {
        return 0;
    }

    $deleted = 0;
    $files = glob(CACHE_DIR . '/*.json');

    if ($files === false) {
        return 0;
    }

    foreach ($files as $file) {
        $fileAge = time() - filemtime($file);
        if ($fileAge > $maxAge) {
            if (@unlink($file)) {
                $deleted++;
            }
        }
    }

    return $deleted;
}

/**
 * Clear cache for a specific user
 *
 * @param string $user GitHub username
 * @return bool True if cache was cleared
 */
function clearUserCache(string $user): bool
{
    if (!is_dir(CACHE_DIR)) {
        return true;
    }

    // Since we use md5 hash, we need to check all files
    // For simplicity, just clear the cache with empty options
    $key = getCacheKey($user, []);
    $filePath = getCacheFilePath($key);

    if (file_exists($filePath)) {
        return @unlink($filePath);
    }

    return true;
}
