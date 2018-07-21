<?php

namespace App\Tests\Domain\Builder;

use App\Domain\Builders\Interfaces\UserBuilderInterface;
use App\Domain\Builders\UserBuilder;
use App\Domain\DTO\Security\UserRegistrationDTO;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserBuilderTest extends TestCase
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    private $builder;

    protected function setUp()
    {
        $encoder = $this->createMock(PasswordEncoderInterface::class);
        $encoder->method('encodePassword')
                ->willReturn('$2y$10$7J3Aa2d0qHShV1lZObKT/.dKbFMYCHApGJXjK.PrJ..AdFnbGugpa');

        $this->encoderFactory = $this->createMock(EncoderFactoryInterface::class);
        $this->encoderFactory->method('getEncoder')
                             ->willReturn($encoder);

        $this->builder = new UserBuilder($this->encoderFactory);
    }

    public function testConstructor()
    {
        self::assertInstanceOf(UserBuilderInterface::class, $this->builder);
    }

    public function testTheCreationOfUserMethodAndGetterWithDTO()
    {
        $userRegistrationDTO = new UserRegistrationDTO(
            'JohnDoe',
            'John',
            'Doe',
            'john.doe@gmail.com',
            '12345678'
        );

        $this->builder->createUser($userRegistrationDTO);

        self::assertInstanceOf(UserInterface::class, $this->builder->getUser());
    }
}
