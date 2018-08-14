<?php

namespace App\UI\Responders\Security;

use App\UI\Presenters\Interfaces\Security\PasswordRecoveryPresenterInterface;
use App\UI\Responders\Interfaces\Security\PasswordRecoveryResponderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PasswordRecoveryResponder implements PasswordRecoveryResponderInterface
{
    /**
     * @var PasswordRecoveryPresenterInterface
     */
    private $presenter;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * PasswordRecoveryResponder constructor.
     * @param PasswordRecoveryPresenterInterface $presenter
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        PasswordRecoveryPresenterInterface $presenter,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->presenter = $presenter;
        $this->urlGenerator = $urlGenerator;
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
            new RedirectResponse($this->urlGenerator->generate('Home')) :
            new Response($this->presenter->passwordRecoveryPresentation($form, $typeName, $mailerSuccess));
    }
}
