<?php

namespace App\Application\Mailers\Security;

use App\Application\Mailers\Interfaces\Security\PasswordRecoveryMailerInterface;
use Swift_Mailer;
use Swift_Message;
use Twig\Environment;

class PasswordRecoveryMailer implements PasswordRecoveryMailerInterface
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;
    /**
     * @var Swift_Message
     */
    private $message;

    /**
     * PasswordRecoveryMailer constructor.
     * @param Swift_Mailer $mailer
     * @param Environment $twig
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __construct(
        Swift_Mailer $mailer,
        Environment $twig
    ) {
        $this->mailer = $mailer;
        $this->message = ($mailer->createMessage())
            ->setFrom('giildo.jm@gmail.com')
            ->setSubject('Récupération du mot de passe')
            ->setBody(
                $twig->render('Security/mailer.html.twig'),
                'text/html'
            );
    }

    /**
     * @param string $mail
     * @return bool
     */
    public function message(string $mail): bool
    {
        $this->message->setTo($mail);

        return $this->mailer->send($this->message);
    }
}
