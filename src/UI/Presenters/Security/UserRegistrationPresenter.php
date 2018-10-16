<?php

namespace App\UI\Presenters\Security;

use App\UI\Presenters\Interfaces\Security\UserRegistrationPresenterInterface;
use Symfony\Component\Form\FormInterface;
use Twig\Environment;

class UserRegistrationPresenter implements UserRegistrationPresenterInterface
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * UserRegistrationPresenter constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function userRegistrationPresentation(FormInterface $form): string
    {
        return $this->twig->render('Security/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
