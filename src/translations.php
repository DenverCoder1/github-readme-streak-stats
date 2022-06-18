<?php

/**
 * Locales
 * -------
 * For a list of supported locale codes, see https://gist.github.com/DenverCoder1/f61147ba26bfcf7c3bf605af7d3382d5
 *
 * Date Format
 * -----------
 * Supplying a date format is optional and will be used instead of the default locale date format.
 * If the default date format for the locale displays correctly, you should omit the date_format parameter.
 *
 * Different year   Same year   Format string
 * --------------   ---------   -------------
 * 10/8/2016        10/8        j/n[/Y]
 * 8/10/2016        8/10        n/j[/Y]
 * 2016.8.10        8.10        [Y.]n.j
 *
 * For info on valid date_format strings, see https://github.com/DenverCoder1/github-readme-streak-stats#date-formats
 */

return [
    // "en" is the default locale
    "en" => [
        "Total Contributions" => "Total Contributions",
        "Current Streak" => "Current Streak",
        "Longest Streak" => "Longest Streak",
        "Present" => "Present",
    ],
    // Locales below are sorted alphabetically
    "de" => [
        "Total Contributions" => "Gesamte Beiträge",
        "Current Streak" => "Aktuelle Serie",
        "Longest Streak" => "Längste Serie",
        "Present" => "Heute",
    ],
    "es" => [
        "Total Contributions" => "Todas Contribuciones",
        "Current Streak" => "Racha Actual",
        "Longest Streak" => "Racha Más Larga",
        "Present" => "Presente",
    ],
    "ja" => [
        "date_format" => "[Y.]n.j",
        "Total Contributions" => "総ｺﾝﾄﾘﾋﾞｭｰｼｮﾝ数",
        "Current Streak" => "現在のストリーク",
        "Longest Streak" => "最長のストリーク",
        "Present" => "今",
    ],
    "pt-br" => [
        "Total Contributions" => "Total de Contribuições",
        "Current Streak" => "Atual Sequência",
        "Longest Streak" => "Maior Sequência",
        "Present" => "Atualmente",
    ],
    "tr" => [
        "Total Contributions" => "Toplam Katkı",
        "Current Streak" => "Güncel Seri",
        "Longest Streak" => "En Uzun Seri",
        "Present" => "Şu an",
    ],
    "zh" => [
        "Total Contributions" => "合计贡献",
        "Current Streak" => "最近连续贡献",
        "Longest Streak" => "最长连续贡献",
        "Present" => "至今",
    ],
];
