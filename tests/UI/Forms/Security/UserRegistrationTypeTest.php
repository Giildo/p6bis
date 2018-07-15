<?php

namespace App\Tests\UI\Forms\Security;

use App\Domain\DTO\Interfaces\Security\UserRegistrationDTOInterface;
use App\UI\Forms\Security\UserRegistrationType;
use Symfony\Component\Form\Test\TypeTestCase;

class UserRegistrationTypeTest extends TypeTestCase
{
    private $form;

    public function setUp()
    {
        parent::setUp();

        $this->form = $this->factory->create(UserRegistrationType::class);
    }

    public function testReturnOfTheFormType()
    {
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

        $this->form->submit($formData);

        $dto = $this->form->getData();

        self::assertInstanceOf(UserRegistrationDTOInterface::class, $dto);
    }
}
