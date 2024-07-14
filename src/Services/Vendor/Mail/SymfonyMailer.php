<?php

namespace Yugo\Services\Vendor\Mail;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Yugo\Services\Mail;
use Yugo\Services\Vendor\Mail\Mailer as BaseMailer;

class SymfonyMailer extends BaseMailer implements Mail
{
    private Mailer $mailer;

    private Email $mail;

    public function __construct()
    {
        $transport = Transport::fromDsn(vsprintf('smtp://%s:%s', [
            getenv('MAIL_SMTP_HOST'),
            getenv('MAIL_SMTP_PORT'),
        ]));

        $this->mailer = new Mailer($transport);
        $this->mail = new Email;
    }

    public function from(string $address, string $name = null): self
    {
        $this->mail->from(new Address($address, $name));

        return $this;
    }

    public function replyTo(string $address): Mail
    {
        $this->mail->replyTo(new Address($address));

        return $this;
    }

    public function to(string $address): Mail
    {
        $this->mail->to(new Address($address));

        return $this;
    }

    public function subject(string $subject): Mail
    {
        $this->mail->subject($subject);

        return $this;
    }

    public function body(string $message): Mail
    {
        $this->mail->html($message);

        return $this;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function send(): void
    {
        if (empty($this->mail->getFrom())) {
            $this->mail->from(new Address(
                getenv('MAIL_FROM_ADDRESS'),
                getenv('MAIL_FROM_NAME'),
            ));
        }

        $this->mail->getHeaders()
            ->addTextHeader('X-Powered-By', $this->transport());

        $this->mailer->send($this->mail);
    }
}