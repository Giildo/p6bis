<?php

namespace App\UI\Responders\Security;

use App\UI\Presenters\Interfaces\Security\UserRegistrationPresenterInterface;
use App\UI\Responders\Interfaces\Security\UserRegistrationResponderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

class UserRegistrationResponder implements UserRegistrationResponderInterface
{
    /**
     * @var UserRegistrationPresenterInterface
     */
    private $presenter;

    /**
     * UserRegistrationResponder constructor.
     * @param UserRegistrationPresenterInterface $presenter
     */
    public function __construct(UserRegistrationPresenterInterface $presenter)
    {
        $this->presenter = $presenter;
    }

    /**
     * @param FormInterface $form
     * @return Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function userRegistrationResponse(FormInterface $form)
    {
        return new Response($this->presenter->userRegistrationPresentation($form));
    }
}
