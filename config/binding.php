<?php

use Psr\Log\LoggerInterface;
use Psr\Http\Message\{RequestInterface, ResponseInterface, ServerRequestInterface};
use Yugo\Http\{Request, Response, ServerRequest};
use Yugo\Logger\Log;
use Yugo\Services\{Database, Mail, Queue};
use Yugo\Services\Vendor\Database\SQL;
use Yugo\Services\Vendor\Queue\RedisQueue;
use function DI\create;

return [
    ServerRequestInterface::class => create(ServerRequest::class),
    RequestInterface::class => create(Request::class),
    ResponseInterface::class => create(Response::class),
    LoggerInterface::class => create(Log::class),

    Database::class => create(SQL::class),
    Mail::class => create(sprintf('Yugo\\Services\\Vendor\\Mail\\%s', get_env('MAIL_TRANSPORTER', 'PHPMailer'))),
    Queue::class => create(RedisQueue::class),
];