<?php

namespace App\Tests\Domain\DTO\Security;

use App\Domain\DTO\Security\PasswordRecoveryForPasswordDTO;
use PHPUnit\Framework\TestCase;

class PasswordRecoveryForPasswordDTOTest extends TestCase
{
    public function testConstructorAndAttribute()
    {
        $dto = new PasswordRecoveryForPasswordDTO('12345678');

        self::assertEquals('12345678', $dto->password);
    }
}
