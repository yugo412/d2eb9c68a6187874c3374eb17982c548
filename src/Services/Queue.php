<?php

namespace Yugo\Services;

use Closure;
use Yugo\Jobs\Job;

interface Queue
{
    public function dispatch(Job $job): void;

    public function listen(string $name): string|false;
}