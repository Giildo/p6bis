<?php

namespace App\Domain\Builders\Interfaces;

use App\Domain\Builders\UserBuilder;
use App\Domain\DTO\Security\UserRegistrationDTO;
use App\Domain\Model\Interfaces\UserInterface;

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
     * @return UserInterface
     */
    public function getUser(): UserInterface;
}