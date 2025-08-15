<?php

namespace App\Utils;

class Validator
{
    public static function isStringLengthValid(string $value, int $minLength = 1, int $maxLength = 255): bool
    {
        $length = mb_strlen($value);
        return $length >= $minLength && $length <= $maxLength;
    }

    public static function isYearValid(int $year, int $min = 1800, ?int $max = null): bool
    {
        $max = $max ?? ((int) date('Y')) + 20; // +20 for counting cases, when the user wants to enter info about film that will be released in the future.
        return $year >= $min && $year <= $max;
    }

    public static function isStringAllowedCharsOnly(string $value): bool
    {
        return preg_match('/^[a-zA-Z0-9 ]+$/u', $value) === 1;
    }
}
