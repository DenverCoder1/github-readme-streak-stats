<?php

/**
 * Locales
 * -------
 * For a list of supported locale codes, see https://gist.github.com/DenverCoder1/f61147ba26bfcf7c3bf605af7d3382d5
 * 
 * Date Format
 * -----------
 * Supplying a date format is optional and will be used instead of the default.
 * 
 * Different year   Same year   Format string
 * --------------   ---------   -------------
 * Aug 10, 2016     Aug 10      M j[, Y]
 * 10 Aug 2016      10 Aug      j M[ Y]
 * 2016 Aug 10      Aug 10      [Y ]M j
 * 10/8/2016        10/8        j/n[/Y]
 * 8/10/2016        8/10        n/j[/Y]
 * 2016.8.10        8.10        [Y.]n.j
 * 
 * For info on valid date_format strings, see https://github.com/DenverCoder1/github-readme-streak-stats#date-formats
 */

return [
    "en" => [
        "date_format" => "M j[, Y]",
        "Total Contributions" => "Total Contributions",
        "Current Streak" => "Current Streak",
        "Longest Streak" => "Longest Streak",
    ],
    "es" => [
        "Total Contributions" => "Todas Contribuciones",
        "Current Streak" => "Racha Actual",
        "Longest Streak" => "Racha Más Larga",
    ],
    "ja" => [
        "date_format" => "[Y.]n.j",
        "Total Contributions" => "総ｺﾝﾄﾘﾋﾞｭｰｼｮﾝ数",
        "Current Streak" => "現在のストリーク",
        "Longest Streak" => "最長のストリーク",
    ],
];
