<?php


namespace App\Service;


use Swift_Mailer;
use Symfony\Bridge\Monolog\Handler\SwiftMailerHandler;

class EmailService
{
    /**
     * @var Swift_Mailer $mailer
     */
    private Swift_Mailer $mailer;

    public function __construct(Swift_Mailer $mailer)
    {

        $this->mailer = $mailer;
    }

    /**
     * @param string $sender
     * @param string $receiver
     * @param string $subject
     * @param string $body
     * @param $template
     * @param string $contentType
     * @return int
     */
    public function send(string $sender, string $receiver, string $subject, string $body, $template, string $contentType = ""): int
    {
        $message = (new \Swift_Message($subject))
            ->setFrom($sender)
            ->setTo($receiver)
            ->setBody(
                $body,
                $contentType
            );

        return $this->mailer->send($message);
    }

    public function template(){

    }
}