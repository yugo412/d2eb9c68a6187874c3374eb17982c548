<?php

namespace Yugo\Services\Vendor\Queue;

use Redis;
use Yugo\Jobs\Job;
use Yugo\Services\Queue;

class RedisQueue implements Queue
{
    private Redis $redis;

    private string $queueName = 'queue';

    public function __construct()
    {
        $this->redis = new Redis();
        $this->redis->connect(get_env('REDIS_HOST'), get_env('REDIS_PORT'));
        if (!empty(get_env('REDIS_PASSWORD'))) {
            $this->redis->auth(get_env('REDIS_PASSWORD'));
        }
    }

    public function dispatch(Job $job): void
    {
        $this->redis->rPush($job->name, serialize($job));
    }

    public function listen(string $name): string|false
    {
        return $this->redis->lPop($name);
    }
}