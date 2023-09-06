# FAQ

## How do I create a Readme for my profile?

A profile readme appears on your profile page when you create a repository with the same name as your username and add a `README.md` file to it. For example, the repository for the user [`DenverCoder1`](https://github.com/DenverCoder1) is located at [`DenverCoder1/DenverCoder1`](https://github.com/DenverCoder1/DenverCoder1).

## How do I include GitHub Readme Streak Stats in my Readme?

Markdown files on GitHub support embedded images using Markdown or HTML. You can customize your Streak Stats image on the [demo site](https://streak-stats.demolab.com/demo/) and use the image source in either of the following ways:

### Markdown

```md
[![GitHub Streak](https://streak-stats.demolab.com?user=DenverCoder1)](https://git.io/streak-stats)
```

### HTML

<!-- prettier-ignore-start -->
```html
<a href="https://git.io/streak-stats"><img src="https://streak-stats.demolab.com?user=DenverCoder1"/></a>
```
<!-- prettier-ignore-end -->

## Why doesn't my Streak Stats match my contribution graph?

GitHub Readme Streak Stats uses the GitHub API to fetch your contribution data. These stats are returned in UTC time which may not match your local time. Additionally, due to caching, the stats may not be updated immediately after a commit. You may need to wait up to a few hours to see the latest stats.

If you think your stats are not showing up because of a time zone issue, you can try one of the following:

1. Change the date of the commit. You can [adjust the time](https://codewithhugo.com/change-the-date-of-a-git-commit/) of a past commit to make it in the middle of the day.
2. Create a new commit in a repository with the date set to the date that is missing from your streak stats:

```bash
git commit --date="2022-08-02 12:00" -m "Test commit" --allow-empty
git push
```

## What is considered a "contribution"?

Contributions include commits, pull requests, and issues that you create in standalone repositories ([Learn more about what is considered a contribution](https://docs.github.com/articles/why-are-my-contributions-not-showing-up-on-my-profile)).

The longest streak is the highest number of consecutive days on which you have made at least one contribution.

The current streak is the number of consecutive days ending with the current day on which you have made at least one contribution. If you have made a contribution today, it will be counted towards the current streak, however, if you have not made a contribution today, the streak will only count days before today so that your streak will not be zero.

> Note: You may need to wait up to 24 hours for new contributions to show up ([Learn how contributions are counted](https://docs.github.com/articles/why-are-my-contributions-not-showing-up-on-my-profile))

## How do I enable private contributions?

To include contributions in private repositories, turn on the setting for "Private contributions" from the dropdown menu above the contribution graph on your profile page.

## How do I center the image on the page?

To center align images, you must use the HTML syntax and wrap it in an element with the HTML attribute `align="center"`.

<!-- prettier-ignore-start -->
```html
<p align="center">
    <a href="https://git.io/streak-stats"><img src="https://streak-stats.demolab.com?user=DenverCoder1"/></a>
</p>
```
<!-- prettier-ignore-end -->

## How do I make different images for dark mode and light mode?

You can [specify theme context](https://github.blog/changelog/2022-05-19-specify-theme-context-for-images-in-markdown-beta/) using the `<picture>` and `<source>` elements as shown below. The dark mode version appears in the `srcset` of the `<source>` tag and the light mode version appears in the `src` of the `<img>` tag.

<!-- prettier-ignore-start -->
```html
<picture>
    <source media="(prefers-color-scheme: dark)" srcset="https://streak-stats.demolab.com?user=DenverCoder1&theme=dark" />
    <img src="https://streak-stats.demolab.com?user=DenverCoder1&theme=default" />
</picture>
```
<!-- prettier-ignore-end -->

## Why and how do I self-host GitHub Readme Streak Stats?

Self-hosting the code can be done online and only takes a couple minutes. The benefits include better uptime since it will use your own access token so will not run into ratelimiting issues and it allows you to customize the deployment for your own use case.

### [📺 Click here for a video tutorial on how to self-host on Vercel](https://www.youtube.com/watch?v=maoXtlb8t44)

See [Deploying it on your own](https://github.com/DenverCoder1/github-readme-streak-stats?tab=readme-ov-file#-deploying-it-on-your-own) in the Readme for detailed instructions.
