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

### Next Steps

- Check out the [Demo Site](https://streak-stats.demolab.com) or [Options](https://github.com/DenverCoder1/github-readme-streak-stats?tab=readme-ov-file#-options) below for available customizations.

- It is recommended to self-host the project more better reliability. See [Deploying it on your own](https://github.com/DenverCoder1/github-readme-streak-stats?tab=readme-ov-file#-deploying-it-on-your-own) for more details.

[![][hspace]](#) [![Deploy to Heroku](https://www.herokucdn.com/deploy/button.svg)][herokudeploy] [![Deploy to Vercel](https://i.imgur.com/Mb3VLCi.png)][verceldeploy]

## ⚙ Demo Site

Here you can customize your Streak Stats card with a live preview.

<https://streak-stats.demolab.com>

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
|     `excludeDaysLabel`     |       Excluded days of the week text color       |                             **hex code** without `#` or **css color**                              |
|       `date_format`        |  Date format pattern or empty for locale format  |                        See note below on [📅 Date Formats](#-date-formats)                         |
|          `locale`          |  Locale for labels and numbers (Default: `en`)   |                            ISO 639-1 code - See [🗪 Locales](#-locales)                             |
|      `short_numbers`       |  Use short numbers (e.g. 1.5k instead of 1,500)  |                                         `true` or `false`                                          |
|           `type`           |          Output format (Default: `svg`)          |                              Current options: `svg`, `png` or `json`                               |
|           `mode`           |          Streak mode (Default: `daily`)          |             `daily` (contribute daily) or `weekly` (contribute once per Sun-Sat week)              |
|       `exclude_days`       | List of days of the week to exclude from streaks |    Comma-separated list of day abbreviations (Sun, Mon, Tue, Wed, Thu, Fri, Sat) e.g. `Sun,Sat`    |
|    `disable_animations`    |    Disable SVG animations (Default: `false`)     |                                         `true` or `false`                                          |
|        `card_width`        |   Width of the card in pixels (Default: `495`)   |                        Positive integer, minimum width is 100px per column                         |
|       `card_height`        |  Height of the card in pixels (Default: `195`)   |                             Positive integer, minimum height is 170px                              |
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
<table><tbody><tr><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L37"><code>en</code></a> - English<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L37"><img src="https://progress-bar.xyz/100" alt="English 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L47"><code>am</code></a> - አማርኛ<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L47"><img src="https://progress-bar.xyz/100" alt="አማርኛ 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L56"><code>ar</code></a> - العربية<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L56"><img src="https://progress-bar.xyz/100" alt="العربية 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L67"><code>as</code></a> - অসমীয়া<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L67"><img src="https://progress-bar.xyz/100" alt="অসমীয়া 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L84"><code>bho</code></a> - भोजपुरी<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L84"><img src="https://progress-bar.xyz/100" alt="भोजपुरी 100%"></a></td></tr><tr><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L93"><code>bn</code></a> - বাংলা<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L93"><img src="https://progress-bar.xyz/100" alt="বাংলা 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L102"><code>ca</code></a> - català<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L102"><img src="https://progress-bar.xyz/100" alt="català 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L111"><code>ceb</code></a> - Cebuano<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L111"><img src="https://progress-bar.xyz/100" alt="Cebuano 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L120"><code>da</code></a> - dansk<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L120"><img src="https://progress-bar.xyz/100" alt="dansk 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L129"><code>de</code></a> - Deutsch<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L129"><img src="https://progress-bar.xyz/100" alt="Deutsch 100%"></a></td></tr><tr><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L138"><code>el</code></a> - Ελληνικά<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L138"><img src="https://progress-bar.xyz/100" alt="Ελληνικά 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L147"><code>es</code></a> - español<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L147"><img src="https://progress-bar.xyz/100" alt="español 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L156"><code>fa</code></a> - فارسی<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L156"><img src="https://progress-bar.xyz/100" alt="فارسی 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L167"><code>fi</code></a> - suomi<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L167"><img src="https://progress-bar.xyz/100" alt="suomi 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L176"><code>fil</code></a> - Filipino<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L176"><img src="https://progress-bar.xyz/100" alt="Filipino 100%"></a></td></tr><tr><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L185"><code>fr</code></a> - français<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L185"><img src="https://progress-bar.xyz/100" alt="français 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L194"><code>gu</code></a> - ગુજરાતી<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L194"><img src="https://progress-bar.xyz/100" alt="ગુજરાતી 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L203"><code>he</code></a> - עברית<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L203"><img src="https://progress-bar.xyz/100" alt="עברית 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L213"><code>hi</code></a> - हिन्दी<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L213"><img src="https://progress-bar.xyz/100" alt="हिन्दी 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L230"><code>hu</code></a> - magyar<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L230"><img src="https://progress-bar.xyz/100" alt="magyar 100%"></a></td></tr><tr><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L247"><code>id</code></a> - Indonesia<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L247"><img src="https://progress-bar.xyz/100" alt="Indonesia 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L256"><code>it</code></a> - italiano<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L256"><img src="https://progress-bar.xyz/100" alt="italiano 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L265"><code>ja</code></a> - 日本語<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L265"><img src="https://progress-bar.xyz/100" alt="日本語 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L276"><code>jv</code></a> - Jawa<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L276"><img src="https://progress-bar.xyz/100" alt="Jawa 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L285"><code>kk</code></a> - қазақ тілі<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L285"><img src="https://progress-bar.xyz/100" alt="қазақ тілі 100%"></a></td></tr><tr><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L294"><code>kn</code></a> - ಕನ್ನಡ<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L294"><img src="https://progress-bar.xyz/100" alt="ಕನ್ನಡ 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L303"><code>ko</code></a> - 한국어<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L303"><img src="https://progress-bar.xyz/100" alt="한국어 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L312"><code>mai</code></a> - मैथिली<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L312"><img src="https://progress-bar.xyz/100" alt="मैथिली 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L321"><code>mal</code></a> - മലയാളം<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L321"><img src="https://progress-bar.xyz/100" alt="മലയാളം 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L331"><code>mi</code></a> - Māori<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L331"><img src="https://progress-bar.xyz/100" alt="Māori 100%"></a></td></tr><tr><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L340"><code>mr</code></a> - मराठी<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L340"><img src="https://progress-bar.xyz/100" alt="मराठी 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L349"><code>ms</code></a> - Melayu<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L349"><img src="https://progress-bar.xyz/100" alt="Melayu 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L358"><code>ms_ID</code></a> - Melayu (Indonesia)<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L358"><img src="https://progress-bar.xyz/100" alt="Melayu (Indonesia) 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L367"><code>my</code></a> - မြန်မာ<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L367"><img src="https://progress-bar.xyz/100" alt="မြန်မာ 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L376"><code>ne</code></a> - नेपाली<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L376"><img src="https://progress-bar.xyz/100" alt="नेपाली 100%"></a></td></tr><tr><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L385"><code>nl</code></a> - Nederlands<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L385"><img src="https://progress-bar.xyz/100" alt="Nederlands 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L394"><code>no</code></a> - norsk<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L394"><img src="https://progress-bar.xyz/100" alt="norsk 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L403"><code>pa</code></a> - ਪੰਜਾਬੀ<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L403"><img src="https://progress-bar.xyz/100" alt="ਪੰਜਾਬੀ 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L412"><code>pl</code></a> - polski<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L412"><img src="https://progress-bar.xyz/100" alt="polski 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L421"><code>ps</code></a> - پښتو<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L421"><img src="https://progress-bar.xyz/100" alt="پښتو 100%"></a></td></tr><tr><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L432"><code>pt</code></a> - português<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L432"><img src="https://progress-bar.xyz/100" alt="português 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L441"><code>pt_BR</code></a> - português (Brasil)<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L441"><img src="https://progress-bar.xyz/100" alt="português (Brasil) 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L450"><code>ro</code></a> - română<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L450"><img src="https://progress-bar.xyz/100" alt="română 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L459"><code>ru</code></a> - русский<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L459"><img src="https://progress-bar.xyz/100" alt="русский 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L476"><code>sa</code></a> - संस्कृत भाषा<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L476"><img src="https://progress-bar.xyz/100" alt="संस्कृत भाषा 100%"></a></td></tr><tr><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L485"><code>sd_PK</code></a> - سنڌي (پاڪستان)<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L485"><img src="https://progress-bar.xyz/100" alt="سنڌي (پاڪستان) 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L497"><code>sr_Cyrl</code></a> - српски (ћирилица)<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L497"><img src="https://progress-bar.xyz/100" alt="српски (ћирилица) 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L506"><code>sr_Latn</code></a> - srpski (latinica)<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L506"><img src="https://progress-bar.xyz/100" alt="srpski (latinica) 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L515"><code>su</code></a> - Basa Sunda<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L515"><img src="https://progress-bar.xyz/100" alt="Basa Sunda 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L524"><code>sv</code></a> - svenska<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L524"><img src="https://progress-bar.xyz/100" alt="svenska 100%"></a></td></tr><tr><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L533"><code>sw</code></a> - Kiswahili<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L533"><img src="https://progress-bar.xyz/100" alt="Kiswahili 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L542"><code>ta</code></a> - தமிழ்<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L542"><img src="https://progress-bar.xyz/100" alt="தமிழ் 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L551"><code>tcy</code></a> - Tulu<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L551"><img src="https://progress-bar.xyz/100" alt="Tulu 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L560"><code>te</code></a> - తెలుగు<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L560"><img src="https://progress-bar.xyz/100" alt="తెలుగు 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L569"><code>th</code></a> - ไทย<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L569"><img src="https://progress-bar.xyz/100" alt="ไทย 100%"></a></td></tr><tr><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L578"><code>tr</code></a> - Türkçe<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L578"><img src="https://progress-bar.xyz/100" alt="Türkçe 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L587"><code>uk</code></a> - українська<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L587"><img src="https://progress-bar.xyz/100" alt="українська 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L596"><code>ur_PK</code></a> - اردو (پاکستان)<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L596"><img src="https://progress-bar.xyz/100" alt="اردو (پاکستان) 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L607"><code>vi</code></a> - Tiếng Việt<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L607"><img src="https://progress-bar.xyz/100" alt="Tiếng Việt 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L616"><code>yo</code></a> - Èdè Yorùbá<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L616"><img src="https://progress-bar.xyz/100" alt="Èdè Yorùbá 100%"></a></td></tr><tr><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L626"><code>zh_Hans</code></a> - 中文（简体）<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L626"><img src="https://progress-bar.xyz/100" alt="中文（简体） 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L636"><code>zh_Hant</code></a> - 中文（繁體）<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L636"><img src="https://progress-bar.xyz/100" alt="中文（繁體） 100%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L76"><code>bg</code></a> - български<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L76"><img src="https://progress-bar.xyz/86" alt="български 86%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L222"><code>ht</code></a> - créole haïtien<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L222"><img src="https://progress-bar.xyz/86" alt="créole haïtien 86%"></a></td><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L239"><code>hy</code></a> - հայերեն<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L239"><img src="https://progress-bar.xyz/86" alt="հայերեն 86%"></a></td></tr><tr><td><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L468"><code>rw</code></a> - Kinyarwanda<br /><a href="https://github.com/DenverCoder1/github-readme-streak-stats/blob/main/src/translations.php#L468"><img src="https://progress-bar.xyz/86" alt="Kinyarwanda 86%"></a></td><td></td><td></td><td></td><td></td></tr></tbody></table>
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
| <pre>d F[, Y]</pre> | <pre>"2020-04-14" => "14 April, 2020"<br/><br/>"2024-04-14" => "14 April"</pre> |
|  <pre>j/n/Y</pre>   |   <pre>"2020-04-14" => "14/4/2020"<br/><br/>"2024-04-14" => "14/4/2024"</pre>   |
| <pre>[Y.]n.j</pre>  |     <pre>"2020-04-14" => "2020.4.14"<br/><br/>"2024-04-14" => "4.14"</pre>      |
| <pre>M j[, Y]</pre> |   <pre>"2020-04-14" => "Apr 14, 2020"<br/><br/>"2024-04-14" => "Apr 14"</pre>   |

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

> [!NOTE]
> You may need to wait up to 24 hours for new contributions to show up ([Learn how contributions are counted](https://docs.github.com/articles/why-are-my-contributions-not-showing-up-on-my-profile))

## 📤 Deploying it on your own

It is preferable to host the files on your own server and it takes less than 2 minutes to set up.

Doing this can lead to better uptime and more control over customization (you can modify the code for your usage).

You can deploy the PHP files on any website server with PHP installed including Heroku and Vercel.

The Inkscape dependency is required for PNG rendering, as well as Segoe UI font for the intended rendering. If using Heroku, the buildpacks will install these for you automatically.

### [![Deploy to Vercel](https://github.com/DenverCoder1/github-readme-streak-stats/assets/20955511/5a503e6b-c462-4627-82ee-651f2cb2a1fc)][verceldeploy]

Vercel is the recommended option for hosting the files since it is **free** and easy to set up. Watch the video below or expand the instructions to learn how to deploy to Vercel.

> [!NOTE]
> PNG mode is not supported since Inkscape will not be installed but the default SVG mode will work.

### 📺 [Click here for a video tutorial on how to self-host on Vercel](https://www.youtube.com/watch?v=maoXtlb8t44)

<details>
  <summary><b>Instructions for deploying to Vercel (Free)</b></summary>

### Step-by-step instructions for deploying to Vercel

#### Option 1: Deploy to Vercel quickly with the Deploy button (recommended)

> [!IMPORTANT]
> Make sure that you host the **`vercel`** branch as otherwise you'll get a 404 error from Vercel. You can set the `vercel` branch as default after forking the repo.

1. Click the Deploy button below

[![][hspace]](#) [![Deploy with Vercel](https://i.imgur.com/Mb3VLCi.png)][verceldeploy]

2. Create your repository by filling in a Repository Name and clicking "Create"
3. Visit [this link](https://github.com/settings/tokens/new?description=GitHub%20Readme%20Streak%20Stats) to create a new Personal Access Token (no scopes required)
4. Scroll to the bottom and click **"Generate token"**
5. **Add the token** as a Config Var with the key `TOKEN`:

![vercel environment variables](https://github.com/DenverCoder1/github-readme-streak-stats/assets/20955511/17a433d6-0aaa-4c69-9a53-6d4638318fbb)

6. Click **"Deploy"** at the end of the form
7. Once the app is deployed, click the screenshot of your app or continue to the dashboard to find your domain to use in place of `streak-stats.demolab.com`

![deployment](https://github.com/DenverCoder1/github-readme-streak-stats/assets/20955511/32092461-5983-4fed-b21b-29be55ed85e8)

#### Option 2: Deploy to Vercel manually

1. Sign in to **Vercel** or create a new account at <https://vercel.com>
2. Use the following command to clone the repository: `git clone https://github.com/DenverCoder1/github-readme-streak-stats.git`. If you plan to make changes, you can also fork the repository and clone your fork instead. If you do not have Git installed, you can download it from <https://git-scm.com/downloads>.
3. Navigate to the cloned repository's directory using the command `cd github-readme-streak-stats`
4. Switch to the "vercel" branch using the command `git checkout vercel`
5. Make sure you have the Vercel CLI (Command Line Interface) installed on your system. If not, you can download it from <https://vercel.com/download>.
6. Run the command `vercel` and follow the prompts to link your Vercel account and choose a project name
7. After successful deployment, your app will be available at `<project-name>.vercel.app`
8. Open [this link](https://github.com/settings/tokens/new?description=GitHub%20Readme%20Streak%20Stats) to create a new Personal Access Token on GitHub. You don't need to select any scopes for the token.
9. Scroll to the bottom of the page and click on **"Generate token"**
10. Visit the Vercel dashboard at <https://vercel.com/dashboard> and select your project. Then, click on **"Settings"** and choose **"Environment Variables"**.
11. Add a new environment variable with the key `TOKEN` and the value as the token you generated in step 9, then save your changes
12. (Optional) You can also set the `WHITELIST` environment variable to restrict which GitHub usernames can be accessed through the service. Provide the usernames as a comma-separated list, for example: `user1,user2,user3`. If the variable is not set, information can be requested for any GitHub user.
13. To apply the new environment variable(s), you need to redeploy the app. Run `vercel --prod` to deploy the app to production.

![image](https://user-images.githubusercontent.com/20955511/209588756-8bf5b0cd-9aa6-41e8-909c-97bf41e525b3.png)

> ⚠️ **Note**
> To set up automatic Vercel deployments from GitHub, make sure to turn **off** "Include source files outside of the Root Directory" in the General settings and use `vercel` as the production branch in the Git settings.

</details>

### [![Deploy on Heroku](https://github.com/DenverCoder1/github-readme-streak-stats/assets/20955511/e8b575af-5746-4200-a295-7e7baa448383)][herokudeploy]

Heroku is another great option for hosting the files. All features are supported on Heroku and it is where the default domain is hosted. Heroku is not free, however, and you will need to pay between \$5 and \$7 per month to keep the app running. Expand the instructions below to learn how to deploy to Heroku.

<details>
  <summary><b>Instructions for deploying to Heroku (Paid)</b></summary>

### Step-by-step instructions for deploying to Heroku

1. Sign in to **Heroku** or create a new account at <https://heroku.com>
2. Visit [this link](https://github.com/settings/tokens/new?description=GitHub%20Readme%20Streak%20Stats) to create a new Personal Access Token (no scopes required)
3. Scroll to the bottom and click **"Generate token"**
4. Click the Deploy button below

[![][hspace]](#) [![Deploy to Heroku](https://www.herokucdn.com/deploy/button.svg)][herokudeploy]

5. **Add the token** as a Config Var with the key `TOKEN`:

![heroku config variables](https://user-images.githubusercontent.com/20955511/136292022-a8d9b3b5-d7d8-4a5e-a049-8d23b51ce9d7.png)

6. (Optional) You can also set the `WHITELIST` Config Var to restrict which GitHub usernames can be accessed through the service. Provide the usernames as a comma-separated list, for example: `user1,user2,user3`. If the variable is not set, information can be requested for any GitHub user.
7. Click **"Deploy App"** at the end of the form
8. Once the app is deployed, you can use `<your-app-name>.herokuapp.com` in place of `streak-stats.demolab.com`

</details>

### ![Deploy on your own](https://github.com/DenverCoder1/github-readme-streak-stats/assets/20955511/e36ed842-ab56-473a-83fd-ace5bf968996)

You can transfer the files to any webserver using FTP or other means, then refer to [CONTRIBUTING.md](/CONTRIBUTING.md) for installation steps.

### 🐳 Docker

Docker is a great option for self-hosting with full control over your environment. All features are supported including PNG rendering with Inkscape. Expand the instructions below to learn how to deploy with Docker.

<details>
  <summary><b>Instructions for deploying with Docker</b></summary>

### Step-by-step instructions for deploying with Docker

1. Clone the repository:

   ```bash
   git clone https://github.com/DenverCoder1/github-readme-streak-stats.git
   cd github-readme-streak-stats
   ```

2. Visit https://github.com/settings/tokens/new?description=GitHub%20Readme%20Streak%20Stats to create a new Personal Access Token (no scopes required)

3. Scroll to the bottom and click "Generate token"

4. Build the Docker image:

   ```bash
   docker build -t streak-stats .
   ```

5. Run the container with your GitHub token:

   ```bash
   docker run -d -p 8080:80 -e TOKEN=your_github_token_here streak-stats
   ```

6. You can also optionally set the `WHITELIST` environment variable to restrict which GitHub usernames can be accessed through the service. If the `WHITELIST` variable is not set, information can be requested for any GitHub user.
   Provide the usernames as a comma-separated list, for example:

   ```bash
   docker run -d -p 8080:80 -e TOKEN=your_github_token_here -e WHITELIST=user1,user2,user3 streak-stats
   ```

7. Visit http://localhost:8080 to access your self-hosted instance

</details>

[hspace]: https://user-images.githubusercontent.com/20955511/136058102-b79570bc-4912-4369-b664-064a0ada8588.png
[verceldeploy]: https://vercel.com/new/clone?repository-url=https%3A%2F%2Fgithub.com%2FDenverCoder1%2Fgithub-readme-streak-stats%2Ftree%2Fvercel&env=TOKEN&envDescription=GitHub%20Personal%20Access%20Token%20(no%20scopes%20required)&envLink=https%3A%2F%2Fgithub.com%2Fsettings%2Ftokens%2Fnew%3Fdescription%3DGitHub%2520Readme%2520Streak%2520Stats&project-name=streak-stats&repository-name=github-readme-streak-stats
[herokudeploy]: https://heroku.com/deploy?template=https://github.com/DenverCoder1/github-readme-streak-stats/tree/main

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
