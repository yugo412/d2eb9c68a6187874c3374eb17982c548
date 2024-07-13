<?php

declare(strict_types=1);


include __DIR__ . '/../vendor/autoload.php';

use DI\Container;
use Yugo\Framework;
use Yugo\Jobs\Job;
use Yugo\Services\Queue;

$app = new Framework();

$app->exec(function (Container $container): void {
    while (true) {
        $queue = $container->get(Queue::class);
        $message = $queue->listen(get_env('QUEUE_NAME'));

        if (!empty($message)) {
            $job = unserialize($message);
            if ($job instanceof Job) {
                $job->handle();
            }
        }
    }
});