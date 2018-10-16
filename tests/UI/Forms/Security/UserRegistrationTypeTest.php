<?php

namespace App\Tests\UI\Forms\Security;

use App\Domain\DTO\Interfaces\Security\UserRegistrationDTOInterface;
use App\UI\Forms\Security\UserRegistrationType;
use Symfony\Component\Form\Test\TypeTestCase;

class UserRegistrationTypeTest extends TypeTestCase
{
    public function testReturnOfTheFormType()
    {
        $form = $this->factory->create(UserRegistrationType::class);

        $formData = [
            'username'  => 'JohnDoe',
            'firstName' => 'John',
            'lastName'  => 'Doe',
            'mail'      => [
                'first'   => 'john.doe@gmail.com',
                'second' => 'john.doe@gmail.com'
            ],
            'password'  => [
                'first'   => '12345678',
                'second' => '12345678'
            ],
        ];

        $form->submit($formData);

        $dto = $form->getData();

        self::assertInstanceOf(UserRegistrationDTOInterface::class, $dto);
    }
}
