# GitHub Readme Streak Stats

<p align="center">
  <img src="https://i.imgur.com/GZHodUG.png" width="100px" alt="GitHub Readme Streak Stats Logo"/>
</p>

<h2 align="center">Display your contribution streaks on your GitHub profile</h2>

<p align="center">
  <a href="https://github.com/search?q=extension%3Amd+%22github+readme+streak+stats+herokuapp%22&type=Code" alt="Users" title="Repo users">
    <img src="https://freshidea.com/jonah/app/github-search-results/streak-stats"/></a>
  <a href="https://discord.gg/fPrdqh3Zfu" alt="Discord" title="Dev Pro Tips Discussion & Support Server">
    <img src="https://img.shields.io/discord/819650821314052106?color=7289DA&logo=discord&logoColor=white&style=for-the-badge"/></a>
</p>

## 📋 Table of Contents
- [Quick Setup](#-quick-setup)
- [Demo Site](#-demo-site)
- [Customization Options](#-customization-options)
  - [Themes](#-themes)
  - [Locales](#-locales)
  - [Date Formats](#-date-formats)
- [How Stats Are Calculated](#ℹ️-how-these-stats-are-calculated)
- [Deployment Options](#-deployment-options)
  - [Vercel (Recommended)](#deploy-to-vercel)
  - [Heroku](#deploy-on-heroku)
  - [Self-hosting](#deploy-on-your-own)
- [Contributing](#-contributing)
- [Support](#-support)

## ⚡ Quick Setup

1. Copy this markdown to your GitHub profile README:
   ```md
   [![GitHub Streak](https://streak-stats.demolab.com/?user=DenverCoder1)](https://git.io/streak-stats)
   ```
2. Replace `DenverCoder1` with your GitHub username
3. Star the repo! 😄

### Next Steps

- Explore the [Demo Site](https://streak-stats.demolab.com) for customization options
- Self-host for better reliability (see [Deployment Options](#-deployment-options))

[![][hspace]](#) [![Deploy to Vercel](https://i.imgur.com/Mb3VLCi.png)][verceldeploy] [![Deploy to Heroku](https://www.herokucdn.com/deploy/button.svg)][herokudeploy]

## ⚙ Demo Site

Customize your Streak Stats card with an interactive preview at:

**[streak-stats.demolab.com](https://streak-stats.demolab.com)**

![Demo Site](https://user-images.githubusercontent.com/20955511/114579753-dbac8780-9c86-11eb-97dd-207039f67d20.gif "Demo Site")

## 🔧 Customization Options

The `user` parameter is the only required field. All other parameters are optional.

> **Note**: When using the `theme` parameter, any custom colors will override the theme's defaults.

| Parameter | Description | Example |
|:--------:|:-----------:|:-------:|
| `user` | GitHub username | `DenverCoder1` |
| `theme` | Stats card theme | `dark`, `radical`, etc. [🎨 View all themes](./docs/themes.md) |
| `hide_border` | Hide the border | `true` or `false` |
| `border_radius` | Corner roundness | `0` (sharp) to `248` (ellipse) |
| `background` | Background color | Hex code, CSS color, or gradient |
| `border` | Border color | Hex code or CSS color |
| `stroke` | Stroke line color | Hex code or CSS color |
| `ring` | Current streak ring color | Hex code or CSS color |
| `fire` | Fire icon color | Hex code or CSS color |
| `currStreakNum` | Current streak number color | Hex code or CSS color |
| `sideNums` | Total/longest streak number color | Hex code or CSS color |
| `currStreakLabel` | Current streak label color | Hex code or CSS color |
| `sideLabels` | Total/longest streak label color | Hex code or CSS color |
| `dates` | Date range text color | Hex code or CSS color |
| `excludeDaysLabel` | Excluded days color | Hex code or CSS color |
| `date_format` | Date format pattern | See [Date Formats](#-date-formats) |
| `locale` | Language for labels & numbers | ISO code - See [Locales](#-locales) |
| `short_numbers` | Use abbreviated numbers | `true` or `false` |
| `type` | Output format | `svg`, `png`, or `json` |
| `mode` | Streak calculation mode | `daily` or `weekly` |
| `exclude_days` | Days to exclude from streaks | e.g., `Sun,Sat` |
| `disable_animations` | Turn off animations | `true` or `false` |
| `card_width` | Width in pixels | Min: `100px` per column |
| `card_height` | Height in pixels | Min: `170px` |
| `hide_total_contributions` | Hide total contributions | `true` or `false` |
| `hide_current_streak` | Hide current streak | `true` or `false` |
| `hide_longest_streak` | Hide longest streak | `true` or `false` |
| `starting_year` | Starting year for contributions | `2005` or later |

### 🖌 Themes

Add `&theme=` followed by the theme name to use a pre-designed color scheme:

```md
[![GitHub Streak](https://streak-stats.demolab.com/?user=DenverCoder1&theme=dark)](https://git.io/streak-stats)
```

**Popular themes:**
- `default`
- `dark`
- `highcontrast`

**[View all available themes](./docs/themes.md)**

Want to create a theme? See [Issue #32](https://github.com/DenverCoder1/github-readme-streak-stats/issues/32) for contribution guidelines.

### 🗪 Locales

Set the `locale` parameter to display labels in your preferred language:

```md
[![GitHub Streak](https://streak-stats.demolab.com/?user=DenverCoder1&locale=fr)](https://git.io/streak-stats)
```

Currently supporting 50+ languages including English, Spanish, German, French, Japanese, Chinese, and many more.

To help translate Streak Stats into more languages, see [Issue #236](https://github.com/DenverCoder1/github-readme-streak-stats/issues/236).

### 📅 Date Formats

Customize how dates are displayed with the `date_format` parameter:

```md
[![GitHub Streak](https://streak-stats.demolab.com/?user=DenverCoder1&date_format=M%20j%5B,%20Y%5D)](https://git.io/streak-stats)
```

The format uses [PHP's date format](https://www.php.net/manual/en/datetime.format.php) with brackets around the year part, which will be omitted when showing the current year.

**Examples:**
- `d F[, Y]` → "14 April, 2020" or "14 April" (current year)
- `j/n/Y` → "14/4/2020" or "14/4/2024"
- `[Y.]n.j` → "2020.4.14" or "4.14" (current year)
- `M j[, Y]` → "Apr 14, 2020" or "Apr 14" (current year)

## ℹ️ How These Stats Are Calculated

- **Data Source**: Your GitHub profile's contribution graph
- **Private Contributions**: Included if enabled in your GitHub profile settings
- **Contribution Types**: Commits, pull requests, and issues in standalone repos
- **Longest Streak**: Highest number of consecutive days with at least one contribution
- **Current Streak**: Consecutive days with contributions ending with the current day

> **Note**: New contributions may take up to 24 hours to appear ([Learn how GitHub counts contributions](https://docs.github.com/articles/why-are-my-contributions-not-showing-up-on-my-profile))

## 📤 Deployment Options

Self-hosting is recommended for better uptime and customization control.

### Deploy to Vercel

[![Deploy to Vercel](https://i.imgur.com/Mb3VLCi.png)][verceldeploy]

**Recommended** - Free and easy setup, SVG mode only (no PNG support)

<details>
  <summary><b>Step-by-step Vercel deployment instructions</b></summary>

#### Quick Deploy (Recommended)
1. Click the [Deploy to Vercel][verceldeploy] button
2. Create a repository name and click "Create"
3. Create a [GitHub Personal Access Token](https://github.com/settings/tokens/new?description=GitHub%20Readme%20Streak%20Stats) (no scopes needed)
4. Add the token as a Config Var named `TOKEN`
5. Click "Deploy"
6. Use your domain (e.g., `your-app.vercel.app`) instead of `streak-stats.demolab.com`

> ⚠️ If you get libssl or Node 20.x errors, change the Node.js version to 18.x in your Vercel project settings.

#### Manual Deploy
1. Sign in to [Vercel](https://vercel.com) or create an account
2. Clone the repository: `git clone https://github.com/DenverCoder1/github-readme-streak-stats.git`
3. Navigate to the directory: `cd github-readme-streak-stats`
4. Switch to the Vercel branch: `git checkout vercel`
5. Install the [Vercel CLI](https://vercel.com/download)
6. Run `vercel` and follow the prompts
7. Create a [GitHub Personal Access Token](https://github.com/settings/tokens/new?description=GitHub%20Readme%20Streak%20Stats)
8. Add the token as an environment variable named `TOKEN` in your Vercel project settings
9. Run `vercel --prod` to deploy to production

</details>

### Deploy on Heroku

[![Deploy to Heroku](https://www.herokucdn.com/deploy/button.svg)][herokudeploy]

Full-featured but requires a paid plan ($5-7/month)

<details>
  <summary><b>Step-by-step Heroku deployment instructions</b></summary>

1. Sign in to [Heroku](https://heroku.com) or create an account
2. Create a [GitHub Personal Access Token](https://github.com/settings/tokens/new?description=GitHub%20Readme%20Streak%20Stats)
3. Click the [Deploy to Heroku][herokudeploy] button
4. Add your token as a Config Var named `TOKEN`
5. Click "Deploy App"
6. Use your app name (e.g., `your-app.herokuapp.com`) instead of `streak-stats.demolab.com`

</details>

### Deploy on Your Own

Transfer files to any web server with PHP support. See [CONTRIBUTING.md](/CONTRIBUTING.md) for installation requirements.

[hspace]: https://user-images.githubusercontent.com/20955511/136058102-b79570bc-4912-4369-b664-064a0ada8588.png
[verceldeploy]: https://vercel.com/new/clone?repository-url=https%3A%2F%2Fgithub.com%2FDenverCoder1%2Fgithub-readme-streak-stats%2Ftree%2Fvercel&env=TOKEN&envDescription=GitHub%20Personal%20Access%20Token%20(no%20scopes%20required)&envLink=https%3A%2F%2Fgithub.com%2Fsettings%2Ftokens%2Fnew%3Fdescription%3DGitHub%2520Readme%2520Streak%2520Stats&project-name=streak-stats&repository-name=github-readme-streak-stats
[herokudeploy]: https://heroku.com/deploy?template=https://github.com/DenverCoder1/github-readme-streak-stats/tree/main

## 🤗 Contributing

Contributions are welcome! Please:
- [Open an issue](https://github.com/DenverCoder1/github-readme-streak-stats/issues/new/choose) for bugs or feature requests
- [Submit a pull request](https://github.com/DenverCoder1/github-readme-streak-stats/compare) with improvements

Check [CONTRIBUTING.md](/CONTRIBUTING.md) for detailed guidance on requirements and local setup.

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
