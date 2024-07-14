<?php

namespace Yugo\Jobs;

use DI\Container;

abstract class Job
{
    public string $name = 'queue';

    abstract function handle(?Container $container = null): void;
}