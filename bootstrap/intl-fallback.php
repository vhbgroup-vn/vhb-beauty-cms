<?php

// The hosting account currently does not expose PHP's intl extension to the
// web process. Laravel's Number helper only needs a small subset of
// NumberFormatter for the Filament admin UI, so provide a conservative
// fallback until the extension is enabled by the hosting provider.
if (! extension_loaded('intl') && ! class_exists('NumberFormatter', false)) {
    class NumberFormatter
    {
        public const DECIMAL = 1;

        public const CURRENCY = 2;

        public const PERCENT = 3;

        public const SPELLOUT = 4;

        public const ORDINAL = 5;

        public const TYPE_DOUBLE = 1;

        public const TYPE_INT32 = 2;

        public const MAX_FRACTION_DIGITS = 6;

        public const FRACTION_DIGITS = 7;

        public const DEFAULT_RULESET = 8;

        private ?int $maxFractionDigits = null;

        private ?int $fractionDigits = null;

        public function __construct(
            private readonly string $locale,
            private readonly int $style,
        ) {}

        public function setAttribute(int $attribute, int|float $value): bool
        {
            if ($attribute === self::MAX_FRACTION_DIGITS) {
                $this->maxFractionDigits = (int) $value;
            } elseif ($attribute === self::FRACTION_DIGITS) {
                $this->fractionDigits = (int) $value;
            }

            return true;
        }

        public function setTextAttribute(int $attribute, string $value): bool
        {
            return true;
        }

        public function format(int|float $number): string|false
        {
            if ($this->style === self::PERCENT) {
                $number *= 100;
                $suffix = '%';
            } else {
                $suffix = '';
            }

            $decimals = $this->fractionDigits ?? $this->maxFractionDigits ?? 3;
            $formatted = number_format($number, $decimals, '.', ',');

            if ($this->fractionDigits === null) {
                $formatted = rtrim(rtrim($formatted, '0'), '.');
            }

            return $formatted.$suffix;
        }

        public function parse(string $string, int $type = self::TYPE_DOUBLE): int|float|false
        {
            $value = str_replace(',', '', trim($string));

            if (! is_numeric($value)) {
                return false;
            }

            return $type === self::TYPE_INT32 ? (int) $value : (float) $value;
        }

        public function formatCurrency(int|float $number, string $currency): string|false
        {
            return trim($this->format($number).' '.$currency);
        }
    }
}
