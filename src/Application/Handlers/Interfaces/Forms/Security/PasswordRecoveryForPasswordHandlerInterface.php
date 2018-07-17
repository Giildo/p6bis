<?php

namespace App\Application\Handlers\Interfaces\Forms\Security;

use App\Domain\Model\User;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

interface PasswordRecoveryForPasswordHandlerInterface
{
    /**
     * @param FormInterface $form
     * @param Request $request
     * @return bool
     */
    public function handle(FormInterface $form, Request $request): bool;
}