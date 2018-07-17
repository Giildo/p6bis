<?php

namespace App\UI\Responders\Interfaces\Security;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

interface PasswordRecoveryResponderInterface
{
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
    ): Response;
}