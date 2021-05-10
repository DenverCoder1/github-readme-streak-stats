<?php $THEMES = include "../themes.php"; ?>

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
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-48CYVH0XEF');
    </script>
    <title>GitHub Readme Streak Stats Demo</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap">
    <link href="https://css.gg/css?=|moon|sun" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/toggle-dark.css">
    <script type="text/javascript" src="./js/script.js" defer></script>
    <script type="text/javascript" src="./js/accordion.js" defer></script>
    <script type="text/javascript" src="./js/toggle-dark.js" defer></script>
    <script type="text/javascript" src="./js/jscolor.min.js" defer></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</head>

<body <?php echo $_COOKIE["darkmode"] == "on" ? 'data-theme="dark"' : ""; ?>>
    <h1>🔥 GitHub Readme Streak Stats</h1>

    <!-- GitHub badges/links section -->
    <div class="github">
        <!-- GitHub Sponsors -->
        <a class="github-button" href="https://github.com/sponsors/denvercoder1"
            data-color-scheme="no-preference: light; light: light; dark: dark;" data-icon="octicon-heart"
            data-size="large" aria-label="Sponsor @denvercoder1 on GitHub">Sponsor</a>
        <!-- View on GitHub -->
        <a class="github-button" href="https://github.com/denvercoder1/github-readme-streak-stats"
            data-color-scheme="no-preference: light; light: light; dark: dark;" data-size="large"
            aria-label="View denvercoder1/github-readme-streak-stats on GitHub">View on GitHub</a>
        <!-- GitHub Star -->
        <a class="github-button" href="https://github.com/denvercoder1/github-readme-streak-stats"
            data-color-scheme="no-preference: light; light: light; dark: dark;" data-icon="octicon-star"
            data-size="large" data-show-count="true" aria-label="Star denvercoder1/github-readme-streak-stats on GitHub">Star</a>
    </div>

    <div class="container">
        <div class="properties">
            <h2>Properties</h2>
            <form class="parameters">
                <label for="user">Username<span title="required">*</span></label>
                <input class="param" type="text" id="user" name="user" placeholder="DenverCoder1" required
                        pattern="^[A-Za-z\d-]{0,39}[A-Za-z\d]$" title="Up to 40 letters or hyphens but not ending with hyphen">

                <label for="theme">Theme</label>
                <select class="param" id="theme" name="theme" placeholder="default">
<?php foreach ($THEMES as $theme => $options): ?>
                    <option><?php echo $theme; ?></option>
<?php endforeach;?>
                </select>

                <label for="hide_border">Hide Border</label>
                <select class="param" id="hide_border" name="hide_border" placeholder="false">
                    <option>false</option>
                    <option>true</option>
                </select>

                <details class="advanced">
                    <summary>⚙ Advanced Options</summary>
                    <div class="content parameters">
                        <label for="theme">Add Property</label>
                        <select id="properties" name="properties" placeholder="background">
<?php foreach ($THEMES["default"] as $option => $color): ?>
                            <option><?php echo $option; ?></option>
<?php endforeach;?>
                        </select>
                        <button class="plus btn" onclick="return preview.addProperty();">+</button>
                    </div>
                </details>

                <input class="btn" type="submit" value="Submit">
            </form>
        </div>

        <div class="output">
            <h2>Preview</h2>
            <img alt="GitHub Readme Streak Stats" src="preview.php?user=" />
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
    </div>

    <a href="javascript:toggleTheme()" class="darkmode" title="toggle dark mode">
        <i class="<?php echo $_COOKIE["darkmode"] == "on" ? 'gg-sun' : "gg-moon"; ?>"></i>
    </a>
</body>

</html>
