<?php

namespace Yugo\Handlers\Controllers;

use DI\Container;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Yugo\Middlewares\Auth;
use Yugo\Services\Mail;

class MailController extends Controller
{
    public array $middlewares = [Auth::class];

    private Mail $mail;

    public function __construct(private readonly Container $container)
    {
        // force change mail transport on the fly
        // set_env('MAIL_TRANSPORTER', 'PHPMailer');

        $this->mail = $this->container->get(Mail::class);

        // get_env('MAIL_TRANSPORTER'); -> SymfonyMailer
        // $this->mail->transport(); -> PHPMailer
    }

    public function send(Response $response): Response
    {
        $response = $response->withStatus(201);

        return $response;
    }

    public function index(RequestInterface $request, Response $response): Response
    {
        return $response;
    }
}