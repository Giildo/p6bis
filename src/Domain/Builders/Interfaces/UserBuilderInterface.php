<?php

namespace App\Domain\Builders\Interfaces;

use App\Domain\DTO\Security\UserRegistrationDTO;
use App\Domain\Model\User;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

interface UserBuilderInterface
{
    public function createUserFromRegistration(
        UserRegistrationDTO $dto,
        PasswordEncoderInterface $encoder
    );

    /**
     * @return User
     */
    public function getUser(): User;
}