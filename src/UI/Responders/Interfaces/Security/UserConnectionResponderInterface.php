<?php

namespace App\UI\Responders\Interfaces\Security;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

interface UserConnectionResponderInterface
{
    /**
     * @param bool|null $redirect
     * @param null|FormInterface $form
     * @param null|AuthenticationException $error
     * @param null|string $lastUserConnected
     * @return Response|RedirectResponse
     */
    public function userConnectionResponse(
        ?bool $redirect = true,
        ?FormInterface $form = null,
        ?AuthenticationException $error = null,
        ?string $lastUserConnected = ''
    ): Response;
}
