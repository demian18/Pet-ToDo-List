<?php
namespace Core;
class Validator
{
    public static function string($string, $min = 5, $max = INF)
    {
        $length = mb_strlen($string, 'UTF-8');

        return $length >= $min && $length <= $max;
    }

    public static function email(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}