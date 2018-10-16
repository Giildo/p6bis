<?php

namespace App\Domain\Builders;

use App\Domain\Builders\Interfaces\UserBuilderInterface;
use App\Domain\DTO\Security\UserRegistrationDTO;
use App\Domain\Model\Interfaces\UserInterface;
use App\Domain\Model\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserBuilder implements UserBuilderInterface
{
    /**
     * @var UserInterface
     */
    private $user;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * UserBuilder constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function createUser(
        UserRegistrationDTO $dto
    ): self {
        $this->user = new User(
            $dto->username,
            $dto->firstName,
            $dto->lastName,
            $dto->mail
        );

        $this->user->changePassword(
            $this->encoder->encodePassword($this->user, $dto->password)
        );

        return $this;
    }

    /**
     * @return UserInterface
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }
}
