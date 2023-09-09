<?php

namespace App\Services;

use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Header\MetadataHeader;
use Symfony\Component\Mailer\Header\TagHeader;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class MailerService
{
    private $mailer;
    private $replyTo;
    public function __construct(MailerInterface $mailer,$replyTo) {
        $this->mailer = $mailer;
        $this->replyTo = $replyTo;
    }

    public function sendEmail($content) {
        $email = (new TemplatedEmail())
            ->from(new Address('idirwalidhakim31@gmail.com', 'Challenge'))
            ->to(new Address('idirwalidhakim32@gmail.com'))
            ->subject('Welcome to My Website')
            ->html($content)
            ->replyTo(new Address($this->replyTo, 'Reply'));;

        try {
            $this->mailer->send($email);
            return 'Email sent successfully';  // ou autre valeur de succès que vous préférez
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}