<?php

if (!function_exists('get_env')) {
    function get_env(string $key, mixed $default = null): string|array|false
    {
        return getenv($key) ?? $default;
    }
}

if (!function_exists('set_env')) {
    function set_env(string $key, mixed $value): void
    {
        putenv("{$key}={$value}");
    }
}