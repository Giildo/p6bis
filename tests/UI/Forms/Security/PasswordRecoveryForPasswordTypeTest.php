<?php

namespace App\Tests\UI\Forms\Security;

use App\Domain\DTO\Interfaces\Security\PasswordRecoveryForPasswordDTOInterface;
use App\UI\Forms\Security\PasswordRecoveryForPasswordType;
use Symfony\Component\Form\Test\TypeTestCase;

class PasswordRecoveryForPasswordTypeTest extends TypeTestCase
{
    public function testReturnOfTheFormType()
    {
        $form = $this->factory->create(PasswordRecoveryForPasswordType::class);

        $formData = [
            'password' => [
                'first'  => '12345678',
                'second' => '12345678',
            ],
        ];

        $form->submit($formData);

        $dto = $form->getData();

        self::assertInstanceOf(PasswordRecoveryForPasswordDTOInterface::class, $dto);
    }
}
