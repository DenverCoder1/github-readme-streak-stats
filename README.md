<p align="center">
  <img src="https://i.imgur.com/GZHodUG.png" width="100px"/>
  <h3 align="center">Github Readme Streak Stats</h3>
</p>

<p align="center">
  Display your total contributions, current streak,
  <br/>
  and longest streak on your GitHub profile README
</p>

<p align="center">
  <a href="https://github.com/search?q=extension%3Amd+%22github+readme+streak+stats+herokuapp%22&type=Code" alt="Users" title="Repo users">
    <img src="https://freshidea.com/jonah/app/github-search-results/streak-stats"/></a>
  <a href="https://discord.gg/fPrdqh3Zfu" alt="Discord" title="Dev Pro Tips Discussion & Support Server">
    <img src="https://img.shields.io/discord/819650821314052106?color=7289DA&logo=discord&logoColor=white&style=for-the-badge"/></a>
</p>

## ⚡ Quick setup

1. Copy-paste the markdown below into your GitHub profile README
2. Replace the value after `?user=` with your GitHub username

```md
[![GitHub Streak](https://streak-stats.demolab.com/?user=DenverCoder1)](https://git.io/streak-stats)
```

3. Star the repo 😄

> **Note** See below for customization options and about deploying the app on your own.

## ⚙ Demo Site

Here you can customize your Streak Stats card with a live preview.

<http://streak-stats.demolab.com>

[![Demo Site](https://user-images.githubusercontent.com/20955511/114579753-dbac8780-9c86-11eb-97dd-207039f67d20.gif "Demo Site")](http://streak-stats.demolab.com/demo/)

## 🔧 Options

The `user` field is the only required option. All other fields are optional.

If the `theme` parameter is specified, any color customizations specified will be applied on top of the theme, overriding the theme's values.

|         Parameter          |                     Details                      |                                              Example                                               |
| :------------------------: | :----------------------------------------------: | :------------------------------------------------------------------------------------------------: |
|           `user`           |        GitHub username to show stats for         |                                           `DenverCoder1`                                           |
|          `theme`           |     The theme to apply (Default: `default`)      |                          `dark`, `radical`, etc. [🎨➜](./docs/themes.md)                           |
|       `hide_border`        |  Make the border transparent (Default: `false`)  |                                         `true` or `false`                                          |
|      `border_radius`       | Set the roundness of the edges (Default: `4.5`)  |                           Number `0` (sharp corners) to `248` (ellipse)                            |
|        `background`        |  Background color (eg. `f2f2f2`, `35,d22,00f`)   | **hex code** without `#`, **css color**, or gradient in the form `angle,start_color,...,end_color` |
|          `border`          |                   Border color                   |                             **hex code** without `#` or **css color**                              |
|          `stroke`          |        Stroke line color between sections        |                             **hex code** without `#` or **css color**                              |
|           `ring`           |   Color of the ring around the current streak    |                             **hex code** without `#` or **css color**                              |
|           `fire`           |          Color of the fire in the ring           |                             **hex code** without `#` or **css color**                              |
|      `currStreakNum`       |              Current streak number               |                             **hex code** without `#` or **css color**                              |
|         `sideNums`         |         Total and longest streak numbers         |                             **hex code** without `#` or **css color**                              |
|     `currStreakLabel`      |               Current streak label               |                             **hex code** without `#` or **css color**                              |
|        `sideLabels`        |         Total and longest streak labels          |                             **hex code** without `#` or **css color**                              |
|          `dates`           |              Date range text color               |                             **hex code** without `#` or **css color**                              |
|       `date_format`        |  Date format pattern or empty for locale format  |                        See note below on [📅 Date Formats](#-date-formats)                         |
|          `locale`          |  Locale for labels and numbers (Default: `en`)   |                            ISO 639-1 code - See [🗪 Locales](#-locales)                             |
|           `type`           |          Output format (Default: `svg`)          |                              Current options: `svg`, `png` or `json`                               |
|           `mode`           |          Streak mode (Default: `daily`)          |             `daily` (contribute daily) or `weekly` (contribute once per Sun-Sat week)              |
|       `exclude_days`       | List of days of the week to exclude from streaks |    Comma-separated list of day abbreviations (Sun, Mon, Tue, Wed, Thu, Fri, Sat) e.g. `Sun,Sat`    |
|    `disable_animations`    |    Disable SVG animations (Default: `false`)     |                                         `true` or `false`                                          |
|        `card_width`        |   Width of the card in pixels (Default: `495`)   |                        Positive integer, minimum width is 100px per column                         |
| `hide_total_contributions` | Hide the total contributions (Default: `false`)  |                                         `true` or `false`                                          |
|   `hide_current_streak`    |    Hide the current streak (Default: `false`)    |                                         `true` or `false`                                          |
|   `hide_longest_streak`    |    Hide the longest streak (Default: `false`)    |                                         `true` or `false`                                          |
|      `starting_year`       |          Starting year of contributions          |   Integer, must be `2005` or later, eg. `2017`. By default, your account creation year is used.    |

### 🖌 Themes

To enable a theme, append `&theme=` followed by the theme name to the end of the source URL:

```md
[![GitHub Streak](https://streak-stats.demolab.com/?user=DenverCoder1&theme=dark)](https://git.io/streak-stats)
```

|     Theme      |                            Preview                            |
| :------------: | :-----------------------------------------------------------: |
|   `default`    |          ![default](https://i.imgur.com/IaTuYdS.png)          |
|     `dark`     |           ![dark](https://i.imgur.com/bUrsjlp.png)            |
| `highcontrast` |       ![highcontrast](https://i.imgur.com/ovrVrTY.png)        |
|  More themes!  | **🎨 [See a list of all available themes](./docs/themes.md)** |

**If you have come up with a new theme you'd like to share with others, please see [Issue #32](https://github.com/DenverCoder1/github-readme-streak-stats/issues/32) for more information on how to contribute.**

### 🗪 Locales

The following are the locales that have labels translated in Streak Stats. The `locale` query parameter accepts any ISO language or locale code, see [here](https://gist.github.com/DenverCoder1/f61147ba26bfcf7c3bf605af7d3382d5) for a list of valid locales. The locale provided will be used for the date format and number format even if translations are not yet available.

<!-- This section is automatically generated by the `translation-progress.php` script. -->
<!-- prettier-ignore-start -->
<!-- TRANSLATION_PROGRESS_START -->
<table><tbody><tr><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L33"><code>en</code></a> - English<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L33"><img src="https://progress-bar.dev/100" alt="English 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L43"><code>am</code></a> - አማርኛ<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L43"><img src="https://progress-bar.dev/100" alt="አማርኛ 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L85"><code>de</code></a> - Deutsch<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L85"><img src="https://progress-bar.dev/100" alt="Deutsch 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L94"><code>el</code></a> - Ελληνικά<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L94"><img src="https://progress-bar.dev/100" alt="Ελληνικά 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L128"><code>he</code></a> - עברית<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L128"><img src="https://progress-bar.dev/100" alt="עברית 100%"></a></td></tr><tr><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L170"><code>it</code></a> - italiano<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L170"><img src="https://progress-bar.dev/100" alt="italiano 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L188"><code>kn</code></a> - ಕನ್ನಡ<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L188"><img src="https://progress-bar.dev/100" alt="ಕನ್ನಡ 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L197"><code>ko</code></a> - 한국어<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L197"><img src="https://progress-bar.dev/100" alt="한국어 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L271"><code>sr</code></a> - српски<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L271"><img src="https://progress-bar.dev/100" alt="српски 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L312"><code>uk</code></a> - українська<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L312"><img src="https://progress-bar.dev/100" alt="українська 100%"></a></td></tr><tr><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L321"><code>ur_PK</code></a> - اردو (پاکستان)<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L321"><img src="https://progress-bar.dev/100" alt="اردو (پاکستان) 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L52"><code>ar</code></a> - العربية<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L52"><img src="https://progress-bar.dev/86" alt="العربية 86%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L61"><code>bg</code></a> - български<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L61"><img src="https://progress-bar.dev/86" alt="български 86%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L69"><code>bn</code></a> - বাংলা<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L69"><img src="https://progress-bar.dev/86" alt="বাংলা 86%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L77"><code>da</code></a> - dansk<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L77"><img src="https://progress-bar.dev/86" alt="dansk 86%"></a></td></tr><tr><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L103"><code>es</code></a> - español<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L103"><img src="https://progress-bar.dev/86" alt="español 86%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L111"><code>fa</code></a> - فارسی<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L111"><img src="https://progress-bar.dev/86" alt="فارسی 86%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L120"><code>fr</code></a> - français<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L120"><img src="https://progress-bar.dev/86" alt="français 86%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L138"><code>hi</code></a> - हिन्दी<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L138"><img src="https://progress-bar.dev/86" alt="हिन्दी 86%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L146"><code>ht</code></a> - Haitian Creole<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L146"><img src="https://progress-bar.dev/86" alt="Haitian Creole 86%"></a></td></tr><tr><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L154"><code>hy</code></a> - հայերեն<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L154"><img src="https://progress-bar.dev/86" alt="հայերեն 86%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L162"><code>id</code></a> - Indonesia<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L162"><img src="https://progress-bar.dev/86" alt="Indonesia 86%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L179"><code>ja</code></a> - 日本語<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L179"><img src="https://progress-bar.dev/86" alt="日本語 86%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L206"><code>mr</code></a> - मराठी<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L206"><img src="https://progress-bar.dev/86" alt="मराठी 86%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L214"><code>nl</code></a> - Nederlands<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L214"><img src="https://progress-bar.dev/86" alt="Nederlands 86%"></a></td></tr><tr><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L222"><code>pl</code></a> - polski<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L222"><img src="https://progress-bar.dev/86" alt="polski 86%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L230"><code>ps</code></a> - پښتو<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L230"><img src="https://progress-bar.dev/86" alt="پښتو 86%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L239"><code>pt_BR</code></a> - português (Brasil)<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L239"><img src="https://progress-bar.dev/86" alt="português (Brasil) 86%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L247"><code>ru</code></a> - русский<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L247"><img src="https://progress-bar.dev/86" alt="русский 86%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L255"><code>rw</code></a> - Kinyarwanda<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L255"><img src="https://progress-bar.dev/86" alt="Kinyarwanda 86%"></a></td></tr><tr><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L263"><code>sa</code></a> - संस्कृत भाषा<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L263"><img src="https://progress-bar.dev/86" alt="संस्कृत भाषा 86%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L280"><code>sv</code></a> - svenska<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L280"><img src="https://progress-bar.dev/86" alt="svenska 86%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L288"><code>sw</code></a> - Kiswahili<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L288"><img src="https://progress-bar.dev/86" alt="Kiswahili 86%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L296"><code>ta</code></a> - தமிழ்<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L296"><img src="https://progress-bar.dev/86" alt="தமிழ் 86%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L304"><code>tr</code></a> - Türkçe<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L304"><img src="https://progress-bar.dev/86" alt="Türkçe 86%"></a></td></tr><tr><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L331"><code>vi</code></a> - Tiếng Việt<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L331"><img src="https://progress-bar.dev/86" alt="Tiếng Việt 86%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L339"><code>yo</code></a> - Èdè Yorùbá<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L339"><img src="https://progress-bar.dev/86" alt="Èdè Yorùbá 86%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L348"><code>zh_Hans</code></a> - 中文（简体）<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L348"><img src="https://progress-bar.dev/86" alt="中文（简体） 86%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L356"><code>zh_Hant</code></a> - 中文（繁體）<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L356"><img src="https://progress-bar.dev/86" alt="中文（繁體） 86%"></a></td><td></td></tr></tbody></table>
<!-- TRANSLATION_PROGRESS_END -->
<!-- prettier-ignore-end -->

**If you would like to help translate the Streak Stats cards, please see [Issue #236](https://github.com/DenverCoder1/github-readme-streak-stats/issues/236) for more information.**

### 📅 Date Formats

If `date_format` is not provided or is empty, the PHP Intl library is used to determine the date format based on the locale specified in the `locale` query parameter.

A custom date format can be specified by passing a string to the `date_format` parameter.

The required format is to use format string characters from [PHP's date function](https://www.php.net/manual/en/datetime.format.php) with brackets around the part representing the year.

When the contribution year is equal to the current year, the characters in brackets will be omitted.

**Examples:**

|     Date Format     |                                     Result                                      |
| :-----------------: | :-----------------------------------------------------------------------------: |
| <pre>d F[, Y]</pre> | <pre>"2020-04-14" => "14 April, 2020"<br/><br/>"2023-04-14" => "14 April"</pre> |
|  <pre>j/n/Y</pre>   |   <pre>"2020-04-14" => "14/4/2020"<br/><br/>"2023-04-14" => "14/4/2023"</pre>   |
| <pre>[Y.]n.j</pre>  |     <pre>"2020-04-14" => "2020.4.14"<br/><br/>"2023-04-14" => "4.14"</pre>      |
| <pre>M j[, Y]</pre> |   <pre>"2020-04-14" => "Apr 14, 2020"<br/><br/>"2023-04-14" => "Apr 14"</pre>   |

### Example

```md
[![GitHub Streak](https://streak-stats.demolab.com/?user=denvercoder1&currStreakNum=2FD3EB&fire=pink&sideLabels=F00&date_format=[Y.]n.j)](https://git.io/streak-stats)
```

## ℹ️ How these stats are calculated

This tool uses the contribution graphs on your GitHub profile to calculate which days you have contributed.

To include contributions in private repositories, turn on the setting for "Private contributions" from the dropdown menu above the contribution graph on your profile page.

Contributions include commits, pull requests, and issues that you create in standalone repositories.

The longest streak is the highest number of consecutive days on which you have made at least one contribution.

The current streak is the number of consecutive days ending with the current day on which you have made at least one contribution. If you have made a contribution today, it will be counted towards the current streak, however, if you have not made a contribution today, the streak will only count days before today so that your streak will not be zero.

> **Note**
> You may need to wait up to 24 hours for new contributions to show up ([Learn how contributions are counted](https://docs.github.com/articles/why-are-my-contributions-not-showing-up-on-my-profile))

## 📤 Deploying it on your own

If you can, it is preferable to host the files on your own server.

Doing this can lead to better uptime and more control over customization (you can modify the code for your usage).

You can deploy the PHP files on any website server with PHP installed or as a Heroku app.

The Inkscape dependency is required for PNG rendering, as well as Segoe UI font for the intended rendering. If using Heroku, the buildpacks will install these for you automatically.

### Deploy Streak Stats instantly

[![Heroku_logo](https://user-images.githubusercontent.com/20955511/136292872-ab2b3918-3350-4878-93a2-aa1f569b095a.png)](https://heroku.com)

<details>
  <summary><b>Instructions for Deploying to Heroku ($5-$7/month)</b></summary>
  
  ### Step-by-step instructions for deploying to Heroku
  
  1. Sign in to **Heroku** or create a new account at <https://heroku.com>
  2. Visit [this link](https://github.com/settings/tokens/new?description=GitHub%20Readme%20Streak%20Stats) to create a new Personal Access Token (no scopes required)
  3. Scroll to the bottom and click **"Generate token"**
  4. Click the Deploy button below

[![](https://user-images.githubusercontent.com/20955511/136058102-b79570bc-4912-4369-b664-064a0ada8588.png)](#) [![Deploy to Heroku](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy?template=https://github.com/DenverCoder1/github-readme-streak-stats/tree/main "Deploy to Heroku")

5. **Add the token** as a Config Var with the key `TOKEN`:

![heroku config variables](https://user-images.githubusercontent.com/20955511/136292022-a8d9b3b5-d7d8-4a5e-a049-8d23b51ce9d7.png)

6. Click **"Deploy App"** at the end of the form
7. Once the app is deployed, you can use `<your-app-name>.herokuapp.com` in place of `streak-stats.demolab.com`

</details>

[![Vercel_logo](https://user-images.githubusercontent.com/20955511/209479243-5b14048b-e9ae-42da-aec3-1cc88a97aaee.png)](https://vercel.com)

<details>
  <summary><b>Instructions for Deploying to Vercel (Free)</b></summary>
  
### Step-by-step instructions for deploying to Vercel

> **Note** PNG mode is not supported since Inkscape will not be installed.

1. Sign in to **Vercel** or create a new account at <https://vercel.com>
2. Clone this repository with `git clone https://github.com/DenverCoder1/github-readme-streak-stats.git`
   - You may also fork the repository and clone your fork instead if you intend to make changes
3. Enter the directory with `cd github-readme-streak-stats`
4. Switch branches to the `vercel` branch with `git checkout vercel`
5. Make sure you have the [Vercel CLI](https://vercel.com/download) installed
6. Run `vercel` and follow the prompts to link your Vercel account and select a project name
7. The app will be deployed to `<project-name>.vercel.app`
8. Visit [this link](https://github.com/settings/tokens/new?description=GitHub%20Readme%20Streak%20Stats) to create a new Personal Access Token (no scopes required)
9. Scroll to the bottom and click **"Generate token"**
10. Visit the [Vercel dashboard](https://vercel.com/dashboard) and select your project, then click **"Settings"**, then **"Environment Variables"**.
11. Add a new variable with the key `TOKEN` and the value as your token from step 9 and click "Save".
12. For the environment variable to be available, you will need to redeploy the app. Run `vercel --prod` to deploy to production.

![image](https://user-images.githubusercontent.com/20955511/209588756-8bf5b0cd-9aa6-41e8-909c-97bf41e525b3.png)

> **Note** To set up automatic Vercel deployments from GitHub, make sure to turn **off** "Include source files outside of the Root Directory" in the General settings and use `vercel` as the production branch in the Git settings.

</details>

## 🤗 Contributing

Contributions are welcome! Feel free to [open an issue](https://github.com/DenverCoder1/github-readme-streak-stats/issues/new/choose) or submit a [pull request](https://github.com/DenverCoder1/github-readme-streak-stats/compare) if you have a way to improve this project.

Make sure your request is meaningful and you have tested the app locally before submitting a pull request.

Refer to [CONTRIBUTING.md](/CONTRIBUTING.md) for more details on contributing, installing requirements, and running the application.

## 🙋‍♂️ Support

💙 If you like this project, give it a ⭐ and share it with friends!

<p align="left">
  <a href="https://www.youtube.com/channel/UCipSxT7a3rn81vGLw9lqRkg?sub_confirmation=1"><img alt="Youtube" title="Youtube" src="https://img.shields.io/badge/-Subscribe-red?style=for-the-badge&logo=youtube&logoColor=white"/></a>
  <a href="https://github.com/sponsors/DenverCoder1"><img alt="Sponsor with Github" title="Sponsor with Github" src="https://img.shields.io/badge/-Sponsor-ea4aaa?style=for-the-badge&logo=github&logoColor=white"/></a>
</p>

[☕ Buy me a coffee](https://ko-fi.com/jlawrence)

---

Made with ❤️ and PHP

<a href="https://heroku.com/"><img alt="Powered by Heroku" title="Powered by Heroku" src="https://img.shields.io/badge/-Powered%20by%20Heroku-6567a5?style=for-the-badge&logo=heroku&logoColor=white"/></a>
