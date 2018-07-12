<?php

namespace App\Application\Handlers\Forms\Security;

use App\Application\Handlers\Interfaces\Forms\Security\UserConnectionHandlerInterface;
use Symfony\Component\Form\FormInterface;

class UserConnectionHandler implements UserConnectionHandlerInterface
{
    /**
     * @param FormInterface $form
     *
     * @return bool
     */
    public function handle(FormInterface $form): bool
    {
        return $form->isSubmitted() && $form->isValid();
    }
}
