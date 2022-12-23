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
 *
 * Right-to-Left Language Support
 * ------------------------------
 * To enable right-to-left language support, add `"rtl" => true` to the locale array (see "he" for an example).
 */

return [
    // "en" is the default locale
    "en" => [
        "Total Contributions" => "Total Contributions",
        "Current Streak" => "Current Streak",
        "Longest Streak" => "Longest Streak",
        "Week Streak" => "Week Streak",
        "Longest Week Streak" => "Longest Week Streak",
        "Present" => "Present",
    ],
    // Locales below are sorted alphabetically
    "ar" => [
        "rtl" => true,
        "Total Contributions" => "إجمالي المساهمات",
        "Current Streak" => "حالِيا سلسلة متتالية",
        "Longest Streak" => "طَويل سلسلة متتالية",
        "Week Streak" => "أُسْبوع سلسلة متتالية",
        "Longest Week Streak" => "طَويل أُسْبوع سلسلة متتالية",
        "Present" => "الحاضر",
    ],
    "bn" => [
        "Total Contributions" => "মোট অবদান",
        "Current Streak" => "কারেন্ট স্ট্রীক",
        "Longest Streak" => "দীর্ঘতম স্ট্রিক",
        "Week Streak" => "সপ্তাহ স্ট্রীক",
        "Longest Week Streak" => "দীর্ঘতম সপ্তাহ স্ট্রিক",
        "Present" => "বর্তমান",
    ],
    "da" => [
        "Total Contributions" => "Totalt Antal Bidrag",
        "Current Streak" => "Nuværende i Træk",
        "Longest Streak" => "Længst i Træk",
        "Present" => "I dag",
    ],
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
        "Week Streak" => "Racha Semanal",
        "Longest Week Streak" => "Racha Semanal Más Larga",
        "Present" => "Presente",
    ],
    "fa" => [
        "rtl" => true,
        "Total Contributions" => "مجموع مشارکت ها",
        "Current Streak" => "پی‌رفت فعلی",
        "Longest Streak" => "طولانی ترین پی‌رفت",
        "Week Streak" => "پی‌رفت هفته",
        "Longest Week Streak" => "طولانی ترین پی‌رفت هفته",
        "Present" => "اکنون",
    ],
    "fr" => [
        "Total Contributions" => "Contributions totales",
        "Current Streak" => "Séquence actuelle",
        "Longest Streak" => "Plus longue séquence",
        "Present" => "Aujourd'hui",
    ],
    "he" => [
        "rtl" => true,
        "Total Contributions" => "סכום התרומות",
        "Current Streak" => "רצף נוכחי",
        "Longest Streak" => "רצף הכי ארוך",
        "Week Streak" => "רצף שבועי",
        "Longest Week Streak" => "רצף שבועי הכי ארוך",
        "Present" => "היום",
    ],
    "hi" => [
        "Total Contributions" => "कुल योगदान",
        "Current Streak" => "निरंतर दैनिक योगदान",
        "Longest Streak" => "सबसे लंबा दैनिक योगदान",
        "Week Streak" => "सप्ताहिक योगदान",
        "Longest Week Streak" => "दीर्घ साप्ताहिक योगदान",
        "Present" => "आज तक",
    ],
    "id" => [
        "Total Contributions" => "Total Kontribusi",
        "Current Streak" => "Aksi Saat Ini",
        "Longest Streak" => "Aksi Terpanjang",
        "Present" => "Sekarang",
    ],
    "it" => [
        "Total Contributions" => "Tutti i contributi",
        "Current Streak" => "Serie corrente",
        "Longest Streak" => "Serie più lunga",
        "Present" => "Presente",
    ],
    "ja" => [
        "date_format" => "[Y.]n.j",
        "Total Contributions" => "総ｺﾝﾄﾘﾋﾞｭｰｼｮﾝ数",
        "Current Streak" => "現在のストリーク",
        "Longest Streak" => "最長のストリーク",
        "Week Streak" => "週間ストリーク",
        "Longest Week Streak" => "最長の週間ストリーク",
        "Present" => "今",
    ],
    "kn" => [
        "Total Contributions" => "ಒಟ್ಟು ಕೊಡುಗೆ",
        "Current Streak" => "ಪ್ರಸ್ತುತ ಸ್ಟ್ರೀಕ್",
        "Longest Streak" => "ದೊಡ್ಡ ಸ್ಟ್ರೀಕ್",
        "Present" => "ಪ್ರಸ್ತುತ",
    ],
    "ko" => [
        "Total Contributions" => "총 기여 수",
        "Current Streak" => "현재 연속 기여 수",
        "Longest Streak" => "최대 연속 기여 수",
        "Week Streak" => "주간 기여 수",
        "Longest Week Streak" => "최대 주간 기여 수",
        "Present" => "현재",
    ],
    "mr" => [
        "Total Contributions" => "एकूण योगदान",
        "Current Streak" => "साध्यकालीन सातत्यता",
        "Longest Streak" => "दीर्घकालीन सातत्यता",
        "Week Streak" => "साप्ताहिक सातत्यता",
        "Longest Week Streak" => "दीर्घकालीन साप्ताहिक सातत्यता",
        "Present" => "आज पर्यंत",
    ],
    "nl" => [
        "Total Contributions" => "Totale Bijdrage",
        "Current Streak" => "Huidige Serie",
        "Longest Streak" => "Langste Serie",
        "Present" => "Vandaag",
    ],
    "pl" => [
        "Total Contributions" => "Suma Kontrybucji",
        "Current Streak" => "Aktualna Seria",
        "Longest Streak" => "Najdłuższa Seria",
        "Week Streak" => "Seria Tygodni",
        "Longest Week Streak" => "Najdłuższa Seria Tygodni",
        "Present" => "Dziś",
    ],
    "pt-br" => [
        "Total Contributions" => "Total de Contribuições",
        "Current Streak" => "Sequência Atual",
        "Longest Streak" => "Maior Sequência",
        "Week Streak" => "Sequência Semanal",
        "Longest Week Streak" => "Maior Sequência Semanal",
        "Present" => "Presente",
    ],
    "ru" => [
        "Total Contributions" => "Общий вклад",
        "Current Streak" => "Текущая серия",
        "Longest Streak" => "Самая длинная серия",
        "Present" => "Сейчас",
    ],
    "ta" => [
        "Total Contributions" => "மொத்த\nபங்களிப்புகள்",
        "Current Streak" => "மிக சமீபத்திய பங்களிப்புகள்",
        "Longest Streak" => "நீண்ட\nபங்களிப்புகள்",
        "Present" => "இன்றுவரை",
    ],
    "tr" => [
        "Total Contributions" => "Toplam Katkı",
        "Current Streak" => "Güncel Seri",
        "Longest Streak" => "En Uzun Seri",
        "Present" => "Şu an",
    ],
    "vi" => [
        "Total Contributions" => "Tổng số đóng góp",
        "Current Streak" => "Chuỗi đóng góp\nhiện tại",
        "Longest Streak" => "Chuỗi đóng góp lớn nhất",
        "Present" => "Hiện tại",
    ],
    "zh" => [
        "Total Contributions" => "合计贡献",
        "Current Streak" => "目前连续贡献",
        "Longest Streak" => "最长连续贡献",
        "Week Streak" => "周连续贡献",
        "Longest Week Streak" => "最长周连续贡献",
        "Present" => "至今",
    ],
    "zh_Hant" => [
        "Total Contributions" => "合計貢獻",
        "Current Streak" => "目前連續貢獻",
        "Longest Streak" => "最長連續貢獻",
        "Week Streak" => "周連續貢獻",
        "Longest Week Streak" => "最常周連續貢獻",
        "Present" => "至今",
    ],
];
