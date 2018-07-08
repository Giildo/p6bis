<?php

namespace App\Domain\Builders\Interfaces;

use App\Domain\DTO\Security\UserRegistrationDTO;
use App\Domain\Model\User;

interface UserBuilderInterface
{
    public function createUserFromRegistration(
        UserRegistrationDTO $dto
    );

    /**
     * @return User
     */
    public function getUser(): User;
}