<?php

namespace Yugo\Jobs;

use DI\Container;
use Yugo\Logger\Log;
use Yugo\Services\Mail;

class SendMail extends Job
{
    public function __construct(
        private readonly array $mails,
        private readonly Mail $mailer,
    )
    {
        $this->name = get_env('QUEUE_NAME');
    }


    public function handle(?Container $container = null): void
    {
        if (!is_null($container)) {
            $log = $container->get(Log::class);
            $log->info('Sending an email.', [
                'to' => $this->mails['to'],
            ]);
        }
        $this->mailer->to($this->mails['to'])
            ->subject($this->mails['subject'])
            ->body($this->mails['body'])
            ->send();
    }
}