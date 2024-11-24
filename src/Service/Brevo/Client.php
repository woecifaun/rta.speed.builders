<?php

namespace App\Service\Brevo;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * Instance will be in charge of communicating with Brevo API
 */
class Client
{
    function __construct(private MailerInterface $mailer, private string $sender){}

    public function sendSignupValidationLink(string $address, string $name, string $link)
    {
        $params = [
            'subscriber' => $name,
            'confirmationlink' => $link
        ];

        $email = (new Email())
            ->from($this->sender) // should
            ->to($address)
            ->text('Welcome to the RTA Speedbuilding Community!')
            ->html('<p>Welcome to the RTA Speedbuilding Community!</p>')
        ;

        $email
            ->getHeaders()
            ->addTextHeader('templateId', 1)
            ->addParameterizedHeader('params', 'params', $params)
        ;

        try {
            $this->mailer->send($email);
        } catch (\Exception $e) {
            throw new Exception("Error Processing Request", 1);
        }
    }
}
