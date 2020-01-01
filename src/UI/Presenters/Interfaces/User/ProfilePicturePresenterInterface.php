<?php

namespace App\UI\Presenters\Interfaces\User;

use App\Domain\Model\Interfaces\UserInterface;
use Symfony\Component\Form\FormInterface;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

interface ProfilePicturePresenterInterface
{
    /**
     * @param FormInterface $form
     * @param UserInterface $user
     *
     * @return string
     *
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function presentation(
        FormInterface $form,
        UserInterface $user
    ): string;
}
