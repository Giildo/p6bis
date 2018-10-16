<?php

namespace App\Application\Handlers\Interfaces\Forms\Security;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

interface PasswordRecoveryForPasswordHandlerInterface
{
    /**
     * @param FormInterface $form
     * @param Request $request
     *
     * @return bool
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function handle(FormInterface $form, Request $request): bool;
}