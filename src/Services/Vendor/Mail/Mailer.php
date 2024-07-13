<?php

namespace Yugo\Services\Vendor\Mail;

use ReflectionClass;

abstract class Mailer
{
    function transport(): string {
        return (new ReflectionClass($this))->getShortName();
    }
}