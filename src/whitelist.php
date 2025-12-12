<?php

/**
 * Check if a GitHub username is allowed based on the whitelist
 *
 * @param string $user GitHub username to check
 * @return bool True if the username is in the whitelist or if the whitelist is empty, false otherwise
 */
function isWhitelisted(string $user): bool
{
    $whitelistStr = $_SERVER["WHITELIST"] ?? $_ENV["WHITELIST"] ?? getenv("WHITELIST") ?? "";
    $whitelist = array_map("trim", array_filter(explode(",", $whitelistStr)));
    return empty($whitelist) || in_array($user, $whitelist, true);
}
