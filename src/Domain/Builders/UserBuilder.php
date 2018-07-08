<?php

namespace App\Domain\Builders;

use App\Domain\Builders\Interfaces\UserBuilderInterface;
use App\Domain\DTO\Security\UserRegistrationDTO;
use App\Domain\Model\User;
use Closure;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserBuilder implements UserBuilderInterface
{
    /**
     * @var User
     */
    private $user;
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public function createUserFromRegistration(
        UserRegistrationDTO $dto
    ): self {
        $encoder = $this->encoderFactory->getEncoder(User::class);

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
