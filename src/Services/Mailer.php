<?php

namespace App\Services;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Mailer
{
    private MailerInterface $mailer;
    public function __construct(MailerInterface $mailer)
    { 
      $this->mailer = $mailer;  
    }
    public function sendMail():void
    {
        $email = (new Email())
        ->from('mail@gmail.com')
        ->to('raphaeltsiory15@gmail.com')
        ->html(
            "
            <h1>Hello</h1>
            "
        );
        $this->mailer->send($email);
    }
}


?>