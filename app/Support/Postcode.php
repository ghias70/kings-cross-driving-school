<?php

namespace App\Support;

class Postcode
{
    public static function outward(string $postcode): ?string
    {
        $compact = strtoupper((string) preg_replace('/\s+/', '', trim($postcode)));

        if (preg_match('/^([A-Z]{1,2}\d[A-Z\d]?)(\d[A-Z]{2})$/', $compact, $matches)) {
            return $matches[1];
        }

        if (preg_match('/^[A-Z]{1,2}\d[A-Z\d]?$/', $compact)) {
            return $compact;
        }

        return null;
    }

    public static function format(string $postcode): ?string
    {
        $compact = strtoupper((string) preg_replace('/\s+/', '', trim($postcode)));

        if (preg_match('/^([A-Z]{1,2}\d[A-Z\d]?)(\d[A-Z]{2})$/', $compact, $matches)) {
            return $matches[1].' '.$matches[2];
        }

        if (preg_match('/^[A-Z]{1,2}\d[A-Z\d]?$/', $compact)) {
            return $compact;
        }

        return null;
    }

    public static function covers(string $postcode): bool
    {
        $outward = self::outward($postcode);

        if ($outward === null) {
            return false;
        }

        $codes = config('school.postcodes', []);
        usort($codes, fn (string $a, string $b) => strlen($b) <=> strlen($a));

        foreach ($codes as $code) {
            if ($outward === $code) {
                return true;
            }

            if (! str_starts_with($outward, $code)) {
                continue;
            }

            $rest = substr($outward, strlen($code));

            if ($rest !== '' && preg_match('/^[A-Z]+$/', $rest) === 1) {
                return true;
            }
        }

        return false;
    }
}
