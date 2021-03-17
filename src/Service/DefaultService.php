<?php


namespace App\Service;


class DefaultService
{
    public function sendMail($email,$body,$sujet,\Swift_Mailer $mailer){
        $message = (new \Swift_Message($sujet))
            ->setFrom('sac3g8@gmail.com')
            ->setTo($email)
            ->setBody(
                $body
                ,
                'text/html'
            )
        ;
        $mailer->send($message);
    }
}
