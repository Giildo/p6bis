<?php

namespace App\Application\Handlers\Interfaces\Forms\Security;

use Symfony\Component\Form\FormInterface;

interface UserRegistrationHandlerInterface
{
    /**
     * @param FormInterface $form
     * @return bool
     */
    public function handle(FormInterface $form): bool;
}