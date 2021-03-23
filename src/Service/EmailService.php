<?php


namespace App\Service;


use Swift_Mailer;
use Symfony\Bridge\Monolog\Handler\SwiftMailerHandler;

class EmailService
{

    /**
     * @param string $sender
     * @param string $receiver
     * @param string $subject
     * @param string $body
     * @param $template
     * @param string $contentType
     * @param Swift_Mailer $mailer
     * @return bool
     */
    public function send(Swift_Mailer $mailer, string $sender, string $receiver, string $subject, string $body, $template, string $contentType = ""): bool
    {

        $message = (new \Swift_Message($subject))
            ->setFrom($sender)
            ->setTo($receiver)
            ->setBody(
                $body,
                $contentType
            );

        $mailer->send($message);

        return true;
    }

    public function template()
    {

    }
}