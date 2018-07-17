<?php

namespace App\UI\Responders\Security;

use App\UI\Presenters\Interfaces\Security\UserConnectionPresenterInterface;
use App\UI\Responders\Interfaces\Security\UserConnectionResponderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UserConnectionResponder implements UserConnectionResponderInterface
{
    /**
     * @var UserConnectionPresenterInterface
     */
    private $presenter;

    /**
     * UserConnectionResponder constructor.
     * @param UserConnectionPresenterInterface $presenter
     */
    public function __construct(UserConnectionPresenterInterface $presenter)
    {
        $this->presenter = $presenter;
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
            new RedirectResponse('/') :
            new Response($this->presenter->userConnectionPresentation(
                $form,
                $error,
                $lastUserConnected
            ));
    }
}
