<?php

namespace App\Tests\UI\Actions\Security;

use App\UI\Actions\Security\UserDisconnectionAction;
use Exception;
use PHPUnit\Framework\TestCase;

class UserDisconnectionActionTest extends TestCase
{
    public function testTheMethodException()
    {
        $action = new UserDisconnectionAction();

        $this->expectException(Exception::class);

        $action->logout();
    }
}
