<?php

namespace App\UI\Responders\Interfaces\Security;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

interface UserRegistrationResponderInterface
{
    /**
     * @param FormInterface $form
     * @return Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function userRegistrationResponse(FormInterface $form);
}