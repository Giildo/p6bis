<?php

namespace App\UI\Responders\Interfaces\Trick;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

interface NewTrickResponderInterface
{

    /**
     * @param bool $redirection
     * @param null|FormInterface $form
     *
     * @return Response
     *
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function response(
        bool $redirection,
        ?FormInterface $form = null
    ): Response;
}
