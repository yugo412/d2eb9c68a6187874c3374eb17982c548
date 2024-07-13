<?php

namespace Yugo\Handlers\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Yugo\Middlewares\Auth;

class MailController extends Controller
{
    public array $middlewares = [Auth::class];

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