<?php

namespace App\Application\Mailers\Interfaces\Security;

use App\Domain\Model\Interfaces\UserInterface;

interface PasswordRecoveryMailerInterface
{
    /**
     * @param UserInterface $user
     * @return bool
     */
    public function message(UserInterface $user): bool;
}