<?php

namespace App\Domain\Builders\Interfaces;

use App\Domain\DTO\Security\UserRegistrationDTO;
use App\Domain\Model\Interfaces\UserInterface;

interface UserBuilderInterface
{
    /**
     * @param UserRegistrationDTO $dto
     *
     * @return UserBuilderInterface
     */
    public function build(
        UserRegistrationDTO $dto
    ): self;

    /**
     * @return UserInterface
     */
    public function getUser(): UserInterface;
}