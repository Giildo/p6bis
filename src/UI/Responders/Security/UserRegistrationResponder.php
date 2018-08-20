<?php

namespace App\UI\Responders\Security;

use App\UI\Presenters\Interfaces\Security\UserRegistrationPresenterInterface;
use App\UI\Responders\Interfaces\Security\UserRegistrationResponderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserRegistrationResponder implements UserRegistrationResponderInterface
{
    /**
     * @var UserRegistrationPresenterInterface
     */
    private $presenter;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * UserRegistrationResponder constructor.
     * @param UserRegistrationPresenterInterface $presenter
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        UserRegistrationPresenterInterface $presenter,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->presenter = $presenter;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param bool|null $redirection
     * @param FormInterface|null $form
     * @return Response|RedirectResponse
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function userRegistrationResponse(
        ?bool $redirection = true,
        ?FormInterface $form = null
    ): Response {
        return $redirection ?
            new RedirectResponse($this->urlGenerator->generate('Authentication_user_connection')) :
            new Response($this->presenter->userRegistrationPresentation($form));
    }
}
