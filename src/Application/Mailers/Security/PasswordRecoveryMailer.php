<?php

namespace App\Application\Mailers\Security;

use App\Application\Mailers\Interfaces\Security\PasswordRecoveryMailerInterface;
use App\Domain\Model\Interfaces\UserInterface;
use Swift_Mailer;
use Twig\Environment;

class PasswordRecoveryMailer implements PasswordRecoveryMailerInterface
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;
    /**
     * @var Environment
     */
    private $twig;

    /**
     * PasswordRecoveryMailer constructor.
     * @param Swift_Mailer $mailer
     * @param Environment $twig
     */
    public function __construct(
        Swift_Mailer $mailer,
        Environment $twig
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function message(UserInterface $user): bool
    {
        $message = $this->mailer->createMessage();

        $header = $message->embed(\Swift_Image::fromPath(__DIR__ .'/../../../../public/pic/general/header_mail.jpg'));
        $logo = $message->embed(\Swift_Image::fromPath(__DIR__ .'/../../../../public/pic/general/logo.png'));

        $message->setFrom('giildo.jm@gmail.com')
            ->setSubject('Récupération du mot de passe')
            ->setTo($user->getMail())
            ->setBody(
                $this->twig->render('Security/mailer.html.twig', compact('user', 'header', 'logo')),
                'text/html'
            );

        return $this->mailer->send($message);
    }
}
