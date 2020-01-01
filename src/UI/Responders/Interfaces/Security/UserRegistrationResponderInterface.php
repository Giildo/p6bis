<?php

namespace App\UI\Responders\Interfaces\Security;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

interface UserRegistrationResponderInterface
{
    /**
     * @param bool|null $redirection
     * @param FormInterface|null $form
     *
     * @return Response|RedirectResponse
     *
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function userRegistrationResponse(
        ?bool $redirection = true,
        ?FormInterface $form = null
    ): Response;
}