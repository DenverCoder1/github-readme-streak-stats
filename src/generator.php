<?php

declare(strict_types=1);

/**
 * Generate streak stats for a GitHub user from request-style parameters.
 *
 * @param string $user GitHub username to get stats for
 * @param array<string,mixed> $params Options that affect fetching and streak calculation
 * @return array<string,mixed> The calculated streak stats
 */
function generateStreakStats(string $user, array $params = []): array
{
    $user = preg_replace("/[^a-zA-Z0-9\-]/", "", $user);
    if ($user === "") {
        throw new InvalidArgumentException("GitHub username is required.", 400);
    }

    $startingYear = isset($params["starting_year"]) ? intval($params["starting_year"]) : null;
    $mode = isset($params["mode"]) ? strval($params["mode"]) : null;
    $excludeDaysRaw = isset($params["exclude_days"]) ? strval($params["exclude_days"]) : "";

    // Build cache options based on request parameters
    $cacheOptions = [
        "starting_year" => $startingYear,
        "mode" => $mode,
        "exclude_days" => $excludeDaysRaw,
    ];

    // Check if cache is disabled
    $useCache = !isset($_SERVER["DISABLE_CACHE"]) || strtolower(strval($_SERVER["DISABLE_CACHE"])) !== "true";

    // Check for cached stats first (24 hour cache) unless cache is disabled
    $cachedStats = $useCache ? getCachedStats($user, $cacheOptions) : null;

    if ($cachedStats !== null) {
        return $cachedStats;
    }

    // Fetch fresh data from GitHub API
    $contributionGraphs = getContributionGraphs($user, $startingYear);
    $contributions = getContributionDates($contributionGraphs);

    if ($mode === "weekly") {
        $stats = getWeeklyContributionStats($contributions);
    } else {
        // split and normalize excluded days
        $excludeDays = normalizeDays(explode(",", $excludeDaysRaw));
        $stats = getContributionStats($contributions, $excludeDays);
    }

    // Cache the stats for 24 hours unless cache is disabled
    if ($useCache) {
        setCachedStats($user, $cacheOptions, $stats);
    }

    return $stats;
}
