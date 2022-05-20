<?php

if (!function_exists('clear_string')) {
    function clear_string($string)
    {
        $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', ' ', $string);
        $string = preg_replace('/\s+/', ' ', trim($string));

        return $string;
    }
}

if (!function_exists('clear_rate')) {
    function clear_rate($string)
    {
        return preg_replace('/&(amp;)?#?[a-z0-9]+;/i','', $string);
    }
}