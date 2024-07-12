<?php

if (!function_exists('debug')) {
    function debug(mixed $object): void {
        exit(sprintf('<pre>%s</pre>', print_r($object, true)));
    }
}