<?php

namespace App\UI\Responders\Interfaces\User;

use App\Domain\Model\Interfaces\UserInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

interface ProfilePictureResponderInterface
{
    /**
     * @param FormInterface $form
     * @param UserInterface $user
     *
     * @return Response
     *
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function response(
        FormInterface $form,
        UserInterface $user
    ): Response;
}
