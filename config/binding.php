<?php

use Psr\Http\Message\{RequestInterface, ResponseInterface};
use Yugo\Http\{Request, Response};
use Yugo\Services\Mail;
use function DI\create;

$mailTransporter = getenv('MAIL_TRANSPORTER');

return [
    RequestInterface::class => create(Request::class),
    ResponseInterface::class => create(Response::class),

    Mail::class => create(sprintf('Yugo\\Services\\Vendor\\Mail\\%s', $mailTransporter)),
];