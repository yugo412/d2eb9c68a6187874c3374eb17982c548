<?php

namespace Yugo\Services\Vendor\Mail;

use Yugo\Exceptions\MailException;
use Yugo\Services\Mail;

class SimpleMailer extends Mailer implements Mail
{
    private array $addresses;

    private string $subject;

    private string $body;

    private array $headers = [];

    public function from(string $address, ?string $name = null): Mail
    {
        $this->headers['From'] = $address;
        if (!empty($name)) {
            $this->headers['From'] = sprintf('%s <%s>', $name, $address);
        }

        return $this;
    }

    public function replyTo(string $address): Mail
    {
        $this->headers['Reply-To'] = $address;

        return $this;
    }

    public function to(array|string $address): Mail
    {
        $this->addresses = is_string($address) ? [$address] : $address;

        return $this;
    }

    public function subject(string $subject): Mail
    {
        $this->subject = $subject;

        return $this;
    }

    public function body(string $message): Mail
    {
        $this->body = $message;

        return $this;
    }

    /**
     * @throws MailException
     */
    public function send(): void
    {
        $this->headers['X-Powered-By'] = $this->transport();

        $sent = mail(
            implode(', ', $this->addresses),
            $this->subject,
            $this->body,
            $this->headers,
        );

        if (!$sent) {
            throw new MailException(sprintf('Failed to send mail with reason %s', error_get_last()['message'] ?? ''));
        }
    }
}