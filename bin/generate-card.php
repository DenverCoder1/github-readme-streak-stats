<?php

declare(strict_types=1);

$root = dirname(__DIR__);

require_once "{$root}/vendor/autoload.php";
require_once "{$root}/src/stats.php";
require_once "{$root}/src/card.php";
require_once "{$root}/src/cache.php";
require_once "{$root}/src/generator.php";

$dotenv = \Dotenv\Dotenv::createImmutable($root);
$dotenv->safeLoad();

/**
 * Get an environment variable as a nullable string.
 */
function readEnv(string $name): ?string
{
    $value = getenv($name);
    return $value === false || $value === "" ? null : $value;
}

/**
 * Parse action options from a query string or JSON object.
 *
 * @return array<string,string>
 */
function parseActionOptions(string $value): array
{
    $value = trim($value);
    if ($value === "") {
        return [];
    }

    if (str_starts_with($value, "{")) {
        $decoded = json_decode($value, true);
        if (!is_array($decoded)) {
            throw new InvalidArgumentException("Invalid JSON in options.", 1);
        }
        $options = $decoded;
    } else {
        parse_str(ltrim($value, "?"), $options);
    }

    $normalized = [];
    foreach ($options as $key => $optionValue) {
        if ($optionValue === null) {
            continue;
        }
        $normalized[$key] = is_array($optionValue) ? implode(",", $optionValue) : strval($optionValue);
    }

    return $normalized;
}

$args = getopt("", ["options::", "path::"]);
$optionsInput = strval($args["options"] ?? (readEnv("INPUT_OPTIONS") ?? ""));
$outputPath = strval($args["path"] ?? (readEnv("INPUT_PATH") ?? "profile/streak.svg"));

try {
    if (!isset($_SERVER["TOKEN"]) && readEnv("TOKEN") !== null) {
        $_SERVER["TOKEN"] = readEnv("TOKEN");
    }
    if (!isset($_SERVER["TOKEN"]) && readEnv("GITHUB_TOKEN") !== null) {
        $_SERVER["TOKEN"] = readEnv("GITHUB_TOKEN");
    }
    if (!isset($_SERVER["TOKEN"])) {
        throw new RuntimeException("Missing GitHub token. Pass the action token input or set TOKEN.");
    }

    $params = parseActionOptions($optionsInput);
    if (!isset($params["user"]) && readEnv("GITHUB_REPOSITORY_OWNER") !== null) {
        $params["user"] = readEnv("GITHUB_REPOSITORY_OWNER");
    }
    if (($params["type"] ?? "svg") !== "svg") {
        throw new InvalidArgumentException("GitHub Actions generation supports SVG output only.", 1);
    }

    $stats = generateStreakStats($params["user"] ?? "", $params);
    $response = generateOutput($stats, $params);

    $outputDir = dirname($outputPath);
    if ($outputDir !== "." && !is_dir($outputDir) && !mkdir($outputDir, 0755, true)) {
        throw new RuntimeException("Failed to create output directory: {$outputDir}");
    }
    if (file_put_contents($outputPath, $response["body"]) === false) {
        throw new RuntimeException("Failed to write output file: {$outputPath}");
    }

    $githubOutput = readEnv("GITHUB_OUTPUT");
    if ($githubOutput !== null) {
        file_put_contents($githubOutput, "path={$outputPath}\n", FILE_APPEND);
    }

    fwrite(STDOUT, "Wrote {$outputPath}\n");
} catch (Throwable $error) {
    fwrite(STDERR, $error->getMessage() . "\n");
    exit(1);
}
