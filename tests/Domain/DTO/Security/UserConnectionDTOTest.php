<?php

namespace App\Tests\Domain\DTO\Security;

use App\Domain\DTO\Security\UserConnectionDTO;
use PHPUnit\Framework\TestCase;

class UserConnectionDTOTest extends TestCase
{
    private $userConnectionDTO;

    public function setUp()
    {
        $this->userConnectionDTO = new UserConnectionDTO(
            'JohnDoe',
            '12345678'
        );
    }

    public function testConstructor()
    {
        self::assertInstanceOf(UserConnectionDTO::class, $this->userConnectionDTO);
    }

    public function testAttributesValues()
    {
        self::assertEquals('JohnDoe', $this->userConnectionDTO->username);
        self::assertEquals('12345678', $this->userConnectionDTO->password);
    }

    public function testAttributesType()
    {
        self::assertAttributeInternalType('string', 'username', $this->userConnectionDTO);
        self::assertAttributeInternalType('string', 'password', $this->userConnectionDTO);
    }


}
