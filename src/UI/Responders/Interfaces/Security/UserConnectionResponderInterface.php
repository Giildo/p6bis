<?php

namespace App\UI\Responders\Interfaces\Security;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

interface UserConnectionResponderInterface
{
    /**
     * @param null|FormInterface $form
     * @param null|AuthenticationException $error
     * @param null|string $lastUserConnected
     *
     * @return Response|RedirectResponse
     *
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function userConnectionResponse(
        ?FormInterface $form = null,
        ?AuthenticationException $error = null,
        ?string $lastUserConnected = ''
    ): Response;
}
