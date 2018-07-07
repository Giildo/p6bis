<?php

namespace App\Application\Handlers\Forms\Security;

use App\Application\Handlers\Interfaces\Forms\Security\UserRegistrationHandlerInterface;
use Symfony\Component\Form\FormInterface;

class UserRegistrationHandler implements UserRegistrationHandlerInterface
{
    /**
     * @param FormInterface $form
     * @return bool
     */
    public function handle(FormInterface $form): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            return true;
        }

        return false;
    }
}
