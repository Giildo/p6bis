<?php

namespace App\UI\Presenters\Interfaces\Security;

use Symfony\Component\Form\FormInterface;

interface PasswordRecoveryPresenterInterface
{
    /**
     * @param null|FormInterface $form
     * @param null|string $typeName
     * @param bool|null $mailerSuccess
     * @return string | null
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function passwordRecoveryPresentation(
        ?FormInterface $form = null,
        ?string $typeName = '',
        ?bool $mailerSuccess = false
    ): ?string;
}