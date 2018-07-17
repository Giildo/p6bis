<?php

namespace App\Application\Handlers\Interfaces\Forms\Security;

use App\Domain\Model\User;
use Symfony\Component\Form\FormInterface;

interface PasswordRecoveryForUsernameHandlerInterface
{
    /**
     * @param FormInterface $form
     * @return User|null
     * @throws \Doctrine\ORM\ORMException
     */
    public function handle(FormInterface $form): ?User;
}