<?php

namespace Yugo\Handlers\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface as Response;

class MailController
{
    public function send(Response $response): Response
    {
        $response->getBody()->write('Send mail');

        return $response;
    }

    public function index(RequestInterface $request, Response $response): Response
    {
        return $response;
    }
}