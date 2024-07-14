<?php

namespace Yugo\Handlers\Controllers;

use DI\Container;
use Exception;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface as Log;
use Valitron\Validator;
use Yugo\Jobs\SendMail;
use Yugo\Http\Middlewares\Auth;
use Yugo\Services\Database;
use Yugo\Services\Mail;
use Yugo\Services\Queue;

class MailController extends Controller
{
    public array $middlewares = [Auth::class];

    private Mail $mail;

    private Queue $queue;

    private Log $log;

    private Database $db;

    public function __construct(private readonly Container $container)
    {
        // force change mail transport on the fly
        // set_env('MAIL_TRANSPORTER', 'SymfonyMailer');

        $this->mail = $this->container->get(Mail::class);

        // get_env('MAIL_TRANSPORTER'); -> PHPMailer
        // $this->mail->transport(); -> SymfonyMailer

        $this->queue = $this->container->get(Queue::class);
        $this->log = $this->container->get(Log::class);
        $this->db = $this->container->get(Database::class);
    }

    public function send(Request $request): Response
    {
        try {
            $mails = json_decode($request->getBody()->getContents(), true);
            $validator = new Validator($mails);

            $validator->rule('required', ['address', 'body']);
            $validator->rule('array', ['address']);
            $validator->rule('email', ['address.*']);

            if (!$validator->validate()) {
                return new JsonResponse(
                    [
                    'message' => 'Validation errors.',
                    'errors' => $validator->errors(),
                    ], 422
                );
            }

            foreach ($mails['address'] as $to) {
                $this->log->info(
                    'Prepare to send an email.', [
                    'to' => $to,
                    ]
                );

                $mails['to'] = $to;

                $this->queue->dispatch(new SendMail($mails, $this->mail));
            }

            return new EmptyResponse(201);
        } catch (Exception $e) {
            return new JsonResponse(
                [
                'message' => $e->getMessage(),
                ], 500
            );
        }
    }

    public function index(Request $request, Response $response): Response
    {
        $mails = $this->db->statement()
            ->query('SELECT * FROM mails');

        return new JsonResponse(
            array_map(
                function ($mail) {
                    return [
                    'id' => $mail['id'],
                    'from' => $mail['from'],
                    'to' => $mail['to'],
                    'subject' => $mail['subject'],
                    'body' => $mail['body'],
                    ];
                }, $mails->fetchAll()
            )
        );
    }
}