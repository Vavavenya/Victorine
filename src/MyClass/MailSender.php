<?php
/**
 * Created by PhpStorm.
 * User: Боря
 * Date: 20.05.2018
 * Time: 11:02
 */

namespace App\MyClass;


class MailSender
{
    private $text;

    private $send_to;

    public function sendMessage(\Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('Quiz@lol.com')
            ->setTo( $this->send_to)
            ->setBody($this->text);
        $mailer->send($message);
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getSendTo()
    {
        return $this->send_to;
    }

    public function setSendTo($send_to)
    {
        $this->send_to = $send_to;
    }
}