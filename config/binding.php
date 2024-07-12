<?php

use Psr\Http\Message\{RequestInterface, ResponseInterface};
use Yugo\Http\{Request, Response};
use function DI\create;

return [
    RequestInterface::class => create(Request::class),
    ResponseInterface::class => create(Response::class),
];