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

if (!function_exists('dd')) {
    function dd(...$args): void
    {
        dump($args);
        exit;
    }
}

if (!function_exists('now')) {
    function now(bool $immutable = false): DateTime|DateTimeImmutable
    {
        if ($immutable) {
            return new DateTimeImmutable();
        }

        return new DateTime();
    }
}