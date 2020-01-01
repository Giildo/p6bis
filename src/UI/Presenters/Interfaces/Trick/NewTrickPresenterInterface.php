<?php

namespace App\UI\Presenters\Interfaces\Trick;

use Symfony\Component\Form\FormInterface;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

interface NewTrickPresenterInterface
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
    public function newTrickPresentation(FormInterface $form): string;
}
