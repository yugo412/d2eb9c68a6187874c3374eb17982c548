<?php

namespace Yugo\Handlers\Controllers;

use DI\Container;
use Exception;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Yugo\Jobs\SendMail;
use Yugo\Middlewares\Auth;
use Yugo\Services\Mail;
use Yugo\Services\Queue;

class MailController extends Controller
{
    public array $middlewares = [Auth::class];

    private Mail $mail;

    private Queue $queue;

    public function __construct(private readonly Container $container)
    {
        // force change mail transport on the fly
        // set_env('MAIL_TRANSPORTER', 'PHPMailer');

        $this->mail = $this->container->get(Mail::class);

        // get_env('MAIL_TRANSPORTER'); -> SymfonyMailer
        // $this->mail->transport(); -> PHPMailer

        $this->queue = $this->container->get(Queue::class);
    }

    public function send(Request $request, Response $response): Response
    {
        try {
            $mails = json_decode($request->getBody()->getContents(), true);
            foreach ($mails['address'] as $to) {
                $mails['to'] = $to;

                $this->queue->dispatch(new SendMail($mails, $this->mail));
            }

            return new EmptyResponse(201);
        } catch (Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function index(Request $request, Response $response): Response
    {
        return $response;
    }
}