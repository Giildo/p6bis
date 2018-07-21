<?php

namespace App\UI\Responders\Security;

use App\UI\Presenters\Interfaces\Security\UserConnectionPresenterInterface;
use App\UI\Responders\Interfaces\Security\UserConnectionResponderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UserConnectionResponder implements UserConnectionResponderInterface
{
    /**
     * @var UserConnectionPresenterInterface
     */
    private $presenter;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * UserConnectionResponder constructor.
     * @param UserConnectionPresenterInterface $presenter
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        UserConnectionPresenterInterface $presenter,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->presenter = $presenter;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param bool|null $redirect
     * @param null|FormInterface $form
     * @param AuthenticationException|null $error
     * @param null|string $lastUserConnected
     * @return Response|RedirectResponse
     */
    public function userConnectionResponse(
        ?bool $redirect = true,
        ?FormInterface $form = null,
        ?AuthenticationException $error = null,
        ?string $lastUserConnected = ''
    ): Response {
        return $redirect ?
            new RedirectResponse($this->urlGenerator->generate('home')) :
            new Response($this->presenter->userConnectionPresentation(
                $form,
                $error,
                $lastUserConnected
            ));
    }
}
