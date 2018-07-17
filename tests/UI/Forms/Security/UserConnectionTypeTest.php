<?php

namespace App\Tests\UI\Forms\Security;

use App\Domain\DTO\Interfaces\Security\UserConnectionDTOInterface;
use App\UI\Forms\Security\UserConnectionType;
use Symfony\Component\Form\Test\TypeTestCase;

class UserConnectionTypeTest extends TypeTestCase
{
    private $form;

    public function setUp()
    {
        parent::setUp();

        $this->form = $this->factory->create(UserConnectionType::class);
    }

    public function testReturnOfTheFormType()
    {
        $formData = [
            'username'  => 'JohnDoe',
            'password'  => '12345678',
        ];

        $this->form->submit($formData);

        $dto = $this->form->getData();

        self::assertInstanceOf(UserConnectionDTOInterface::class, $dto);
    }
}
