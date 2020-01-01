<?php

namespace App\Tests\UI\Forms\Security;

use App\Domain\DTO\Interfaces\Security\PasswordRecoveryForUsernameDTOInterface;
use App\UI\Forms\Security\PasswordRecoveryForUsernameType;
use Symfony\Component\Form\Test\TypeTestCase;

class PasswordRecoveryForUsernameTypeTest extends TypeTestCase
{
    public function testReturnOfTheFormType()
    {
        $form = $this->factory->create(PasswordRecoveryForUsernameType::class);

        $formData = [
            'username'  => 'JohnDoe',
        ];

        $form->submit($formData);

        $dto = $form->getData();

        self::assertInstanceOf(PasswordRecoveryForUsernameDTOInterface::class, $dto);
    }
}
