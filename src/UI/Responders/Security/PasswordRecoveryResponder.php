<?php

namespace App\UI\Responders\Security;

use App\UI\Presenters\Interfaces\Security\PasswordRecoveryPresenterInterface;
use App\UI\Responders\Interfaces\Security\PasswordRecoveryResponderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class PasswordRecoveryResponder implements PasswordRecoveryResponderInterface
{
    /**
     * @var PasswordRecoveryPresenterInterface
     */
    private $presenter;

    /**
     * PasswordRecoveryResponder constructor.
     * @param PasswordRecoveryPresenterInterface $presenter
     */
    public function __construct(PasswordRecoveryPresenterInterface $presenter)
    {
        $this->presenter = $presenter;
    }

    /**
     * @param bool|null $redirection
     * @param null|FormInterface $form
     * @param null|string $typeName
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function passwordRecoveryResponse(
        ?bool $redirection = true,
        ?FormInterface $form = null,
        ?string $typeName = ''
    ): Response {
        $mailerSuccess = false;

        if (is_null($form)) {
            $mailerSuccess = true;
        }

        return $redirection ?
            new RedirectResponse('/') :
            new Response($this->presenter->passwordRecoveryPresentation($form, $typeName, $mailerSuccess));
    }
}
