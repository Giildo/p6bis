<?php

namespace App\Domain\Builders;

use App\Domain\Builders\Interfaces\UserBuilderInterface;
use App\Domain\DTO\Security\UserRegistrationDTO;
use App\Domain\Model\User;
use Closure;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class UserBuilder implements UserBuilderInterface
{
    /**
     * @var User
     */
    private $user;

    public function createUserFromRegistration(
        UserRegistrationDTO $dto,
        PasswordEncoderInterface $encoder
    ): self {
        $this->user = new User(
            $dto->username,
            $dto->firstName,
            $dto->lastName,
            $dto->mail,
            $dto->password,
            Closure::fromCallable([$encoder, 'encodePassword'])
        );

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
