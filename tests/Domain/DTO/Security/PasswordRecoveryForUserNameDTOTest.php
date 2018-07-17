<?php

namespace App\Tests\Domain\DTO\Security;

use App\Domain\DTO\Security\PasswordRecoveryForUsernameDTO;
use PHPUnit\Framework\TestCase;

class PasswordRecoveryForUserNameDTOTest extends TestCase
{
    public function testConstructorAndAttribute()
    {
        $dto = new PasswordRecoveryForUsernameDTO('JohnDoe');

        self::assertEquals('JohnDoe', $dto->username);
    }
}
