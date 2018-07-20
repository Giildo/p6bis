<?php

namespace App\Domain\Builders\Interfaces;

use App\Domain\Builders\UserBuilder;
use App\Domain\DTO\Security\UserRegistrationDTO;
use App\Domain\Model\User;

interface UserBuilderInterface
{
    /**
     * @param UserRegistrationDTO $dto
     *
     * @return UserBuilder
     */
    public function createUser(
        UserRegistrationDTO $dto
    ): UserBuilder;

    /**
     * @return User
     */
    public function getUser(): User;
}