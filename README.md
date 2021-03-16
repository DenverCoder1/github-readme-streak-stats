<p align="center">
  <img src="https://i.imgur.com/GZHodUG.png" width="100px"/>
  <h3 align="center">Github Readme Streak Stats</h3>
</p>

<p align="center">
  Display your total contributions, current streak,
  <br/>
  and longest streak on your GitHub profile README
</p>

## 📃 Table of Contents

- [📃 Table of Contents](#-table-of-contents)
- [⚡ Quick setup](#-quick-setup)
- [🔧 Options](#-options)
- [🖌 Themes](#-themes)
- [🖼 Theme customizations](#-theme-customizations)
- [ℹ How these stats are calculated](#ℹ-how-these-stats-are-calculated)
- [📤 Deploying it on your own](#-deploying-it-on-your-own)
- [🤗 Contributing](#-contributing)
- [🙋‍♂️ Support](#️-support)

## ⚡ Quick setup

1. Copy-paste the markdown below into your GitHub profile README
2. Replace the value after `?user=` with your GitHub username

```md
[![GitHub Streak](https://github-readme-streak-stats.herokuapp.com/?user=DenverCoder1)](https://github.com/DenverCoder1/github-readme-streak-stats)
```

> Note: See below for information about deploying the app on your own

## 🔧 Options

|      Parameter       |                    Details                     |                            Example                            |
| :------------------: | :--------------------------------------------: | :-----------------------------------------------------------: |
|        `user`        |       GitHub username to show stats for        |                         `DenverCoder1`                        |
|       `theme`        |    The theme to apply (Default: `default`)     |    `dark`, `radical`, etc. [🎨➜](./docs/themes/README.md)    |
|    `hide_border`     | Make the border transparent (Default: `false`) |                       `true` or `false`                       |
| Theme Customizations |           Customize specific colors            |    See [Theme Customizations](#-theme-customizations) below   |

> **Note:** The `user` field is the only required option. All other fields are optional.

## 🖌 Themes

To enable a theme, append `&theme=` followed by the theme name to the end of the source url:


```md
[![GitHub Streak](https://github-readme-streak-stats.herokuapp.com/?user=DenverCoder1&theme=dark)](https://github.com/DenverCoder1/github-readme-streak-stats)
```

|     Theme      |                             Screenshot                              |
| :------------: | :-----------------------------------------------------------------: |
|   `default`    |             ![default](https://i.imgur.com/IaTuYdS.png)             |
|     `dark`     |              ![dark](https://i.imgur.com/bUrsjlp.png)               |
| `highcontrast` |          ![highcontrast](https://i.imgur.com/ovrVrTY.png)           |
|  More themes!  | **🎨 [See a list of all available themes](./docs/themes/README.md)** |

> If you have come up with a new theme you'd like to share with others, open an issue to add it!

## 🖼 Theme customizations

These additional URL options, can be added as parameters to make customizations to the colors used.

If the `theme` parameter is specified, these customizations will be applied on top of the theme, overriding the theme's values.

For each of the following, a **hex code** (ex. `F00`, `FFF2`, `23BFC1`, etc.) or **css color**  (ex. `white`, `forestgreen`, `fuchsia`, etc.) can be specified. Do *not* use `#` when writing the hex code!

|     Parameter     |                   Details                   |
| :---------------: | :-----------------------------------------: |
|   `background`    |              Background color               |
|     `border`      |                Border color                 |
|     `stroke`      |     Stroke line color between sections      |
|      `ring`       | Color of the ring around the current streak |
|      `fire`       |        Color of the fire in the ring        |
|  `currStreakNum`  |            Current streak number            |
|    `sideNums`     |      Total and longest streak numbers       |
| `currStreakLabel` |            Current streak label             |
|   `sideLabels`    |       Total and longest streak labels       |
|      `dates`      |            Date range text color            |

### Example:

```md
[![GitHub Streak](https://github-readme-streak-stats.herokuapp.com/?user=denvercoder1&currStreakNum=2FD3EB&fire=pink&sideLabels=F00)](https://github.com/DenverCoder1/github-readme-streak-stats)
```

## ℹ How these stats are calculated

This tool uses the contribution graphs on your GitHub profile to calculate which days you have contributed.

To include contributions in private repositories, turn on the setting for "Private contributions" from the dropdown menu above the contribution graph on your profile page.

Contributions include commits, pull requests, and issues that you create in standalone repositories ([Learn more about what is considered a contribution](https://docs.github.com/articles/why-are-my-contributions-not-showing-up-on-my-profile)).

The longest streak is the highest number of consecutive days on which you have made at least one contribution.

The current streak is the number of consecutive days ending with the current day on which you have made at least one contribution. If you have made a contribution today, it will be counted towards the current streak, however, if you have not made a contribution today, the streak will only count days before today so that your streak will not be zero.

> Note: You may need to wait up to 24 hours for new contributions to show up ([Learn how contributions are counted](https://docs.github.com/articles/why-are-my-contributions-not-showing-up-on-my-profile))

## 📤 Deploying it on your own

If you can, it is preferable to host the files on your own server.

Doing this can lead to better uptime and more control over customization (you can modify the code for your usage).

You can deploy the PHP files on any website server with PHP installed or as a Heroku app.

[![Deploy](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy?template=https://github.com/DenverCoder1/github-readme-streak-stats/tree/main)

## 🤗 Contributing

Contributions are welcome! Feel free to open an issue or submit a pull request if you have a way to improve this project.

Make sure your request is meaningful and you have tested the app locally before submitting a pull request.

#### Installing PHP (Linux)

Requirements: [PHP 7.4+](https://www.apachefriends.org/index.html)

```
sudo apt-get install php
sudo apt-get install php-curl
```

[▶ How to run PHP using XAMPP (Windows)](https://www.youtube.com/watch?v=K-qXW9ymeYQ)

#### Clone the repository

```
git clone https://github.com/DenverCoder1/github-readme-streak-stats.git
cd github-readme-streak-stats
```

### Running the app locally

```
php -S localhost:8000
```

Open http://localhost:8000/src?user=DenverCoder1 to test the project locally.

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
