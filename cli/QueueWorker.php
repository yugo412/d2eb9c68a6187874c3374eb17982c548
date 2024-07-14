<?php

declare(strict_types=1);


include __DIR__ . '/../vendor/autoload.php';

use DI\Container;
use Yugo\Framework;
use Yugo\Jobs\Job;
use Yugo\Logger\Log;
use Yugo\Services\Queue;

$app = new Framework();

$app->exec(function (Container $container): void {
    while (true) {
        $queue = $container->get(Queue::class);
        $log = $container->get(Log::class);

        $message = $queue->listen(get_env('QUEUE_NAME'));

        if (!empty($message)) {
            $job = unserialize($message);
            if ($job instanceof Job) {
                try {
                    $job->handle($container);
                } catch (Exception $e) {
                    $log->error($e);
                }
            }
        }
    }
});