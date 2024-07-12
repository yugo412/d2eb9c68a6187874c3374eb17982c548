<?php

namespace Yugo\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;

class Auth
{
    public function handle(Response $response): Response
    {
        return $response->withStatus(403);
    }
}