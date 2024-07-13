<?php

namespace Yugo\Services\Vendor\Mail;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer as BasePHPMailer;
use Yugo\Services\Mail;

class PHPMailer extends Mailer implements Mail
{

    private BasePHPMailer $mailer;

    public function __construct()
    {
        $this->mailer = new BasePHPMailer((bool) getenv('MAIL_SMTP_TRANSPORT'));
        if (getenv('MAIL_SMTP_TRANSPORT') === 'smtp') {
            $this->mailer->isSMTP();
            $this->mailer->SMTPSecure = getenv('MAIL_SMTP_ENCRYPTION');
        }

        $this->mailer->Host = getenv('MAIL_SMTP_HOST');
        $this->mailer->Port = getenv('MAIL_SMTP_PORT');
        $this->mailer->Username = getenv('MAIL_SMTP_USERNAME');
        $this->mailer->Password = getenv('MAIL_SMTP_PASSWORD');
    }

    /**
     * @throws Exception
     */
    public function from(string $address, ?string $name = null): Mail
    {
        $this->mailer->setFrom($address, $name);

        return $this;
    }

    /**
     * @throws Exception
     */
    public function replyTo(string $address): Mail
    {
        $this->mailer->addReplyTo($address);

        return $this;
    }

    /**
     * @throws Exception
     */
    public function to(string $address): Mail
    {
        $this->mailer->addAddress($address);

        return $this;
    }

    public function subject(string $subject): Mail
    {
        $this->mailer->Subject = $subject;

        return $this;
    }

    public function body(string $message): Mail
    {
        $this->mailer->Body = $message;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function send(): void
    {
        $this->mailer->addCustomHeader('X-Powered-By', $this->transport());
        $this->mailer->send();
    }
}