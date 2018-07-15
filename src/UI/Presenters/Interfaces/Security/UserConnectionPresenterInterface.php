<?php

namespace App\UI\Presenters\Interfaces\Security;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

interface UserConnectionPresenterInterface
{
    public function userConnectionPresentation(
        FormInterface $form,
        ?AuthenticationException $error = null,
        ?string $lastUserConnected = ''
    ): string;
}
