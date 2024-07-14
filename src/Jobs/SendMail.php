<?php

namespace Yugo\Jobs;

use DI\Container;
use Yugo\Logger\Log;
use Yugo\Services\Database;
use Yugo\Services\Mail;

class SendMail extends Job
{
    public function __construct(
        private readonly array $mails,
        private readonly Mail $mailer,
    ) {
        $this->name = get_env('QUEUE_NAME');
    }


    public function handle(?Container $container = null): void
    {
        if (!is_null($container)) {
            $log = $container->get(Log::class);
            $log->info(
                'Sending an email.', [
                'to' => $this->mails['to'],
                ]
            );

            $db = $container->get(Database::class);
            $statement = $db->statement()->prepare('INSERT INTO mails (transporter, "from", "to", subject, body, sent_at) VALUES (:transporter, :from, :to, :subject, :body, :sent_at)');
            $statement->execute(
                [
                'transporter' => $this->mailer->transport(),
                'from' => get_env('MAIL_FROM_ADDRESS'),
                'to' => $this->mails['to'],
                'subject' => $this->mails['subject'],
                'body' => $this->mails['body'],
                'sent_at' => now()->format('Y-m-d H:i:s'),
                ]
            );
        }
        $this->mailer->to($this->mails['to'])
            ->subject($this->mails['subject'])
            ->body($this->mails['body'])
            ->send();
    }
}