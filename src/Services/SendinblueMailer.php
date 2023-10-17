<?php
//namespace App\Services;
//use Exception;
//use Symfony\Bridge\Twig\Mime\TemplatedEmail;
//use Symfony\Component\Mailer\MailerInterface;
//use Symfony\Component\Mime\Address;
//
//class SendinblueMailer {
//    private $mailer;
//    private $replyTo;
//    public function __construct(MailerInterface $mailer,$replyTo) {
//        $this->mailer = $mailer;
//        $this->replyTo = $replyTo;
//    }
//
//    public function sendEmail($content) {
//        $email = (new TemplatedEmail())
//            ->from(new Address('idirwalidhakim31@gmail.com', 'Challenge'))
//            ->to(new Address('idirwalidhakim32@gmail.com'))
//            ->subject('Welcome to My Website')
//            ->html($content)
//            ->replyTo(new Address($this->replyTo, 'Reply'));;
//
//        try {
//            $this->mailer->send($email);
//            return 'Email sent successfully';  // ou autre valeur de succès que vous préférez
//        } catch (Exception $e) {
//            return $e->getMessage();
//        }
//    }
//}



namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmail;
use SendinBlue\Client\Model\SendSmtpEmailTo;

class SendinblueMailer
{
//    private string $sendinblueApiKey;
    private $replyTo;
    public function __construct(/*string $sendinblueApiKey*/ $replyTo, private MailerService $mailer)
    {
//        $this->sendinblueApiKey = $sendinblueApiKey;
        $this->replyTo = $replyTo;
    }

    public function sendEmail($content)
    {
//        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $this->sendinblueApiKey);
//        dd($this->replyTo);
        // $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');

        $apiInstance = new TransactionalEmailsApi(new Client(), $config);

        $subject = 'Welcome to My Website';
        $body = 'Hello ,<br><br>Thank you for registering on our website!';

        $sendSmtpEmail = new SendSmtpEmail([
            'to' => [new SendSmtpEmailTo(['email' => 'idirwalidhakim31@gmail.com'])],
            'subject' => $subject,
            'htmlContent' => $content,
            'sender' => ['name' => 'Challenge', 'email' => 'idirwalidhakim32@gmail.com', 'replyTo' => $this->replyTo],
            'replyTo' => ['name' => 'Reply', 'email' => $this->replyTo],

        ]);

        try {
            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
            return $result;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
