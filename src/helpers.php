<?php

if (! function_exists('strip_whitespace')) {
    function strip_whitespace(string $value)
    {
        if (! is_null($value)) {
            return str_replace(' ', '', $value);
        }
    }
}
