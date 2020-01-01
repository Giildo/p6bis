<?php

namespace App\Tests\UI\Forms\Security;

use App\Domain\DTO\Interfaces\Security\UserConnectionDTOInterface;
use App\UI\Forms\Security\UserConnectionType;
use Symfony\Component\Form\Test\TypeTestCase;

class UserConnectionTypeTest extends TypeTestCase
{
    public function testReturnOfTheFormType()
    {
        $form = $this->factory->create(UserConnectionType::class);

        $formData = [
            'username'  => 'JohnDoe',
            'password'  => '12345678',
        ];

        $form->submit($formData);

        $dto = $form->getData();

        self::assertInstanceOf(UserConnectionDTOInterface::class, $dto);
    }
}
