<?php

$THEMES = include dirname(__DIR__, 1) . "/themes.php";
$TRANSLATIONS = include dirname(__DIR__, 1) . "/translations.php";
// Get the keys of the first value in the translations array
// and filter to only include locales that have an array as the value
$LOCALES = array_filter(array_keys($TRANSLATIONS), function ($locale) use ($TRANSLATIONS) {
    return is_array($TRANSLATIONS[$locale]);
});

$darkmode = $_COOKIE["darkmode"] ?? null;

/**
 * Convert a camelCase string to a skewer-case string
 * @param string $str The camelCase string
 * @return string The skewer-case string
 */
function camelToSkewer(string $str): string
{
    return preg_replace_callback(
        "/([A-Z])/",
        function ($matches) {
            return "-" . strtolower($matches[0]);
        },
        $str
    );
}

/**
 * Get the file last modified time or the current time if the file doesn't exist
 * 
 * @param string $filename The file name
 * @return int The file last modified time or the current time if the file doesn't exist
 */
function fileModifiedTime(string $filename): int
{
    return file_exists($filename) ? filemtime($filename) : time();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-48CYVH0XEF"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-48CYVH0XEF');
    </script>
    <title>GitHub Readme Streak Stats Demo</title>
    <link href="https://css.gg/css?=|moon|sun" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css?v=<?= fileModifiedTime("./css/style.css") ?>">
    <link rel="stylesheet" href="./css/toggle-dark.css?v=<?= fileModifiedTime("./css/toggle-dark.css") ?>">

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="mask-icon" href="icon.svg" color="#fb8c00">

    <script type="text/javascript" src="./js/script.js?v=<?= fileModifiedTime("./js/script.js") ?>" defer></script>
    <script type="text/javascript" src="./js/accordion.js?v=<?= fileModifiedTime("./js/accordion.js") ?>" defer></script>
    <script type="text/javascript" src="./js/toggle-dark.js?v=<?= fileModifiedTime("./js/toggle-dark.js") ?>" defer></script>
    <script type="text/javascript" src="./js/jscolor.min.js?v=<?= fileModifiedTime("./js/jscolor.min.js") ?>" defer></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</head>

<body <?php echo $darkmode === "on" ? 'data-theme="dark"' : ""; ?>>
    <h1>🔥 GitHub Readme Streak Stats</h1>

    <!-- GitHub badges/links section -->
    <div class="github">
        <!-- GitHub Sponsors -->
        <a class="github-button" href="https://github.com/sponsors/denvercoder1" data-color-scheme="no-preference: light; light: light; dark: dark;" data-icon="octicon-heart" data-size="large" aria-label="Sponsor @denvercoder1 on GitHub">Sponsor</a>
        <!-- View on GitHub -->
        <a class="github-button" href="https://github.com/denvercoder1/github-readme-streak-stats" data-color-scheme="no-preference: light; light: light; dark: dark;" data-size="large" aria-label="View denvercoder1/github-readme-streak-stats on GitHub">View on GitHub</a>
        <!-- GitHub Star -->
        <a class="github-button" href="https://github.com/denvercoder1/github-readme-streak-stats" data-color-scheme="no-preference: light; light: light; dark: dark;" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star denvercoder1/github-readme-streak-stats on GitHub">Star</a>
    </div>

    <div class="container">
        <div class="properties">
            <h2>Properties</h2>
            <form class="parameters">
                <label for="user">Username<span title="required">*</span></label>
                <input class="param" type="text" id="user" name="user" placeholder="DenverCoder1" pattern="^[A-Za-z\d-]{0,39}[A-Za-z\d]$" title="Up to 40 letters or hyphens but not ending with hyphen" />

                <label for="theme">Theme</label>
                <select class="param" id="theme" name="theme">
                    <?php foreach ($THEMES as $theme => $options): ?>
                        <?php
                        $dataAttrs = "";
                        foreach ($options as $key => $value) {
                            // convert key from camelCase to skewer-case
                            $key = camelToSkewer($key);
                            // remove '#' from hex color value
                            $value = preg_replace("/^\#/", "", $value);
                            // add data attribute
                            $dataAttrs .= "data-" . $key . "=\"" . $value . "\" ";
                        }
                        ?>
                        <option value="<?php echo $theme; ?>" <?php echo $dataAttrs; ?>><?php echo $theme; ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="hide-border">Hide Border</label>
                <select class="param" id="hide-border" name="hide_border">
                    <option>false</option>
                    <option>true</option>
                </select>

                <label for="border-radius">Border Radius</label>
                <input class="param" type="number" id="border-radius" name="border_radius" placeholder="4.5" value="4.5" step="0.1" min="0" />

                <label for="locale">Locale</label>
                <select class="param" id="locale" name="locale">
                    <?php foreach ($LOCALES as $locale): ?>
                        <option value="<?php echo $locale; ?>">
                            <?php $display = Locale::getDisplayName($locale, $locale); ?>
                            <?php echo $display . " (" . $locale . ")"; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="date-format">Date Format</label>
                <select class="param" id="date-format" name="date_format">
                    <option value="">default</option>
                    <option value="M j[, Y]">Aug 10, 2016</option>
                    <option value="j M[ Y]">10 Aug 2016</option>
                    <option value="[Y ]M j">2016 Aug 10</option>
                    <option value="j/n[/Y]">10/8/2016</option>
                    <option value="n/j[/Y]">8/10/2016</option>
                    <option value="[Y.]n.j">2016.8.10</option>
                </select>

                <label for="mode">Streak Mode</label>
                <select class="param" id="mode" name="mode">
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                </select>

                <span id="exclude-days-label">Exclude Days</span>
                <div class="checkbox-buttons weekdays" role="group" aria-labelledby="exclude-days-label">
                    <input type="checkbox" value="Sun" id="weekday-sun" />
                    <label for="weekday-sun" data-tooltip="Exclude Sunday" title="Exclude Sunday">S</label>
                    <input type="checkbox" value="Mon" id="weekday-mon" />
                    <label for="weekday-mon" data-tooltip="Exclude Monday" title="Exclude Monday">M</label>
                    <input type="checkbox" value="Tue" id="weekday-tue" />
                    <label for="weekday-tue" data-tooltip="Exclude Tuesday" title="Exclude Tuesday">T</label>
                    <input type="checkbox" value="Wed" id="weekday-wed" />
                    <label for="weekday-wed" data-tooltip="Exclude Wednesday" title="Exclude Wednesday">W</label>
                    <input type="checkbox" value="Thu" id="weekday-thu" />
                    <label for="weekday-thu" data-tooltip="Exclude Thursday" title="Exclude Thursday">T</label>
                    <input type="checkbox" value="Fri" id="weekday-fri" />
                    <label for="weekday-fri" data-tooltip="Exclude Friday" title="Exclude Friday">F</label>
                    <input type="checkbox" value="Sat" id="weekday-sat" />
                    <label for="weekday-sat" data-tooltip="Exclude Saturday" title="Exclude Saturday">S</label>
                    <input type="hidden" id="exclude-days" name="exclude_days" class="param" />
                </div>

                <span id="show-sections-label">Show Sections</span>
                <div class="checkbox-buttons sections" role="group" aria-labelledby="show-sections-label">
                    <input type="checkbox" value="total" id="section-total" checked />
                    <label for="section-total" data-tooltip="Total Contributions" title="Total Contributions">Total</label>
                    <input type="checkbox" value="current" id="section-current" checked />
                    <label for="section-current" data-tooltip="Current Streak" title="Current Streak">Current</label>
                    <input type="checkbox" value="longest" id="section-longest" checked />
                    <label for="section-longest" data-tooltip="Longest Streak" title="Longest Streak">Longest</label>
                    <input type="hidden" id="sections" name="sections" class="param" value="total,current,longest" />
                </div>

                <label for="card-width">Card Width</label>
                <input class="param" type="number" id="card-width" name="card_width" placeholder="495" value="495" step="1" min="300" />

                <label for="type">Output Type</label>
                <select class="param" id="type" name="type">
                    <option value="svg">SVG</option>
                    <option value="png">PNG</option>
                    <option value="json">JSON</option>
                </select>

                <details class="advanced">
                    <summary>⚙ Advanced Options</summary>
                    <div class="content">
                        <div class="radio-buttons parameters" role="radiogroup" aria-labelledby="background-type-label">
                            <!-- Radio buttons to choose between solid and gradient background -->
                            <span id="background-type-label">Background Type</span>
                            <div class="radio-button-group">
                                <div>
                                    <input type="radio" id="background-type-solid" name="background-type" value="solid" checked />
                                    <label for="background-type-solid">Solid</label>
                                </div>
                                <div>
                                    <input type="radio" id="background-type-gradient" name="background-type" value="gradient" />
                                    <label for="background-type-gradient">Gradient</label>
                                </div>
                            </div>
                        </div>
                        <div class="color-properties parameters">
                            <label for="properties">Add Property</label>
                            <select id="properties" name="properties">
                                <?php foreach ($THEMES["default"] as $option => $color): ?>
                                    <option><?php echo $option; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button class="plus btn" type="button" onclick="preview.addProperty()">+</button>
                        </div>
                    </div>
                    <button class="btn" type="button" onclick="preview.exportPhp()">Export to PHP</button>
                    <button id="clear-button" class="btn" type="button" onclick="preview.removeAllProperties()" disabled>Clear Options</button>
                    <textarea id="exported-php" hidden></textarea>
                </details>

                <button class="btn" type="submit">Open Permalink</button>
            </form>
        </div>

        <div class="output top-bottom-split">
            <div class="top">
                <h2>Preview</h2>
                <img alt="GitHub Readme Streak Stats" src="preview.php?user=" />
                <div class="json" style="display: none;">
                    <pre></pre>
                </div>
                <p class="warning">
                    Note: The stats above are just examples and not from your GitHub profile.
                </p>

                <h2>Markdown</h2>
                <div class="md">
                    <code></code>
                </div>

                <button class="copy-button btn tooltip" onclick="clipboard.copy(this);" onmouseout="tooltip.reset(this);" disabled>
                    Copy To Clipboard
                </button>
            </div>
            <div class="bottom">
                <a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/docs/faq.md" target="_blank" class="underline-hover faq">
                    Frequently Asked Questions
                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <g>
                            <path fill="none" d="M0 0h24v24H0z"></path>
                            <path d="M10 6v2H5v11h11v-5h2v6a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h6zm11-3v9l-3.794-3.793-5.999 6-1.414-1.414 5.999-6L12 3h9z"></path>
                        </g>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <a href="javascript:toggleTheme()" class="darkmode" title="toggle dark mode">
        <i class="<?php echo $darkmode === "on" ? "gg-sun" : "gg-moon"; ?>"></i>
    </a>
</body>

</html>
