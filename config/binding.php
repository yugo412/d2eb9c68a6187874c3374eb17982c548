<?php

use Psr\Http\Message\{RequestInterface, ResponseInterface, ServerRequestInterface};
use Yugo\Http\{Request, Response, ServerRequest};
use Yugo\Services\Mail;
use Yugo\Services\Queue;
use Yugo\Services\Vendor\Queue\RedisQueue;
use function DI\create;

$mailTransporter = getenv('MAIL_TRANSPORTER');

return [
    ServerRequestInterface::class => create(ServerRequest::class),
    RequestInterface::class => create(Request::class),
    ResponseInterface::class => create(Response::class),

    Mail::class => create(sprintf('Yugo\\Services\\Vendor\\Mail\\%s', $mailTransporter)),
    Queue::class => create(RedisQueue::class),
];