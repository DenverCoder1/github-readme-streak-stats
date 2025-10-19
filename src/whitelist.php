<?php

/**
 * Check if a GitHub username is allowed based on the whitelist
 *
 * @param string $user GitHub username to check
 * @return bool True if the username is in the whitelist or if the whitelist is empty, false otherwise
 */
function isWhitelisted(string $user): bool
{
    $whitelist = array_map("trim", array_filter(explode(",", $_SERVER["WHITELIST"] ?? "")));
    return empty($whitelist) || in_array($user, $whitelist, true);
}
