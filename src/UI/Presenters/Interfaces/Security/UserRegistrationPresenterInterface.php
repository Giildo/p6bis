<?php

namespace App\UI\Presenters\Interfaces\Security;

use Symfony\Component\Form\FormInterface;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

interface UserRegistrationPresenterInterface
{
    /**
     * @param FormInterface $form
     *
     * @return string
     *
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function userRegistrationPresentation(FormInterface $form): string;
}