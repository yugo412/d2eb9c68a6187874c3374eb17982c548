<?php

namespace Yugo\Jobs;

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


    public function handle(): void
    {
        $this->mailer->to($this->mails['to'])
            ->subject($this->mails['subject'])
            ->body($this->mails['body'])
            ->send();
    }
}