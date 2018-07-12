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
     * @param FormInterface $form
     * @return string
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function userRegistrationPresentation(FormInterface $form): string
    {
        return $this->twig->render('Security/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
