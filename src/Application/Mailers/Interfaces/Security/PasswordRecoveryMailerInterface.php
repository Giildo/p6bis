<?php

namespace App\Application\Mailers\Interfaces\Security;

use Symfony\Component\Security\Core\User\UserInterface;

interface PasswordRecoveryMailerInterface
{
    /**
     * @param UserInterface $user
     * @return bool
     */
    public function message(UserInterface $user): bool;
}