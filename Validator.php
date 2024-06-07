<?php

class Validator
{
    public static function string($string, $min = 5, $max = INF)
    {
        $length = mb_strlen($string, 'UTF-8');

        return $length >= $min && $length <= $max;
    }
}