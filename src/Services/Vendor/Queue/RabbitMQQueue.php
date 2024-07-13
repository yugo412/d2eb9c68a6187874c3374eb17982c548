<?php

namespace Yugo\Services\Vendor\Queue;

use Yugo\Jobs\Job;
use Yugo\Services\Queue;

class RabbitMQQueue implements Queue
{

    public function dispatch(Job $job): void
    {
        // TODO: Implement dispatch() method.
    }

    public function listen(string $name): string|false
    {
        return false;
    }
}