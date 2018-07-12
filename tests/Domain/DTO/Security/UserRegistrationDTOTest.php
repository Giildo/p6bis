<?php

namespace App\Tests\Domain\DTO\Security;

use App\Domain\DTO\Security\UserRegistrationDTO;
use PHPUnit\Framework\TestCase;

class UserRegistrationDTOTest extends TestCase
{
    private $userRegistrationDTO;

    public function setUp()
    {
        $this->userRegistrationDTO = new UserRegistrationDTO(
            'JohnDoe',
            'John',
            'Doe',
            'john.doe@gmail.com',
            '12345678'
        );
    }

    public function testConstructor()
    {
        self::assertInstanceOf(UserRegistrationDTO::class, $this->userRegistrationDTO);
    }

    public function testAttributesValues()
    {
        self::assertEquals('JohnDoe', $this->userRegistrationDTO->username);
        self::assertEquals('John', $this->userRegistrationDTO->firstName);
        self::assertEquals('Doe', $this->userRegistrationDTO->lastName);
        self::assertEquals('john.doe@gmail.com', $this->userRegistrationDTO->mail);
        self::assertEquals('12345678', $this->userRegistrationDTO->password);
    }

    public function testAttributesType()
    {
        self::assertAttributeInternalType('string', 'username', $this->userRegistrationDTO);
        self::assertAttributeInternalType('string', 'firstName', $this->userRegistrationDTO);
        self::assertAttributeInternalType('string', 'lastName', $this->userRegistrationDTO);
        self::assertAttributeInternalType('string', 'mail', $this->userRegistrationDTO);
        self::assertAttributeInternalType('string', 'password', $this->userRegistrationDTO);
    }


}
