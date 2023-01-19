<?php

if (!class_exists("IntlDatePatternGenerator")) {
    /**
     * Mock IntlDatePatternGenerator for when it is not available
     */
    class IntlDatePatternGenerator
    {
        public function __construct(?string $locale = null)
        {
            error_log("Warning: IntlDatePatternGenerator is not available");
        }

        public function getBestPattern(string $skeleton): string
        {
            if ($skeleton === "MMM d") {
                return "M j";
            } elseif ($skeleton === "yyyy MMM d") {
                return "M j, Y";
            } else {
                throw new Exception("Unknown skeleton: " . $skeleton);
            }
        }
    }
}

if (!class_exists("IntlDateFormatter")) {
    /**
     * Mock IntlDateFormatter for when it is not available
     */
    class IntlDateFormatter
    {
        public $pattern;

        public const NONE = 0;
        public const FULL = 1;
        public const LONG = 2;
        public const MEDIUM = 3;
        public const SHORT = 4;
        public const GREGORIAN = 1;

        public function __construct(
            ?string $locale = null,
            int $dateType = IntlDateFormatter::NONE,
            int $timeType = IntlDateFormatter::NONE,
            ?string $timezone = null,
            int $calendar = IntlDateFormatter::GREGORIAN,
            string $pattern = ""
        ) {
            error_log("Warning: IntlDateFormatter is not available");
            $this->pattern = $pattern;
        }

        public function format(DateTimeInterface $date): string
        {
            return date_format($date, $this->pattern);
        }
    }
}

if (!class_exists("NumberFormatter")) {
    /**
     * Mock NumberFormatter for when it is not available
     */
    class NumberFormatter
    {
        public const DECIMAL = 0;

        public function __construct(?string $locale = null, int $pattern = _NumberFormatter::DECIMAL)
        {
            error_log("Warning: NumberFormatter is not available");
        }

        public function format(int $number): string
        {
            return number_format($number);
        }
    }
}

if (!class_exists("Locale")) {
    /**
     * Mock Locale for when it is not available
     */
    class Locale
    {
        public static function getDisplayName(string $locale, string $inLocale): string
        {
            error_log("Warning: Locale is not available");
            return $locale;
        }
    }
}
