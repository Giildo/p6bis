<?php

namespace App\Tests\UI\Forms\Security;

use App\Domain\DTO\Interfaces\Security\PasswordRecoveryForUsernameDTOInterface;
use App\UI\Forms\Security\PasswordRecoveryForUsernameType;
use Symfony\Component\Form\Test\TypeTestCase;

class PasswordRecoveryForUsernameTypeTest extends TypeTestCase
{
    private $form;

    public function setUp()
    {
        parent::setUp();

        $this->form = $this->factory->create(PasswordRecoveryForUsernameType::class);
    }

    public function testReturnOfTheFormType()
    {
        $formData = [
            'username'  => 'JohnDoe',
        ];

        $this->form->submit($formData);

        $dto = $this->form->getData();

        self::assertInstanceOf(PasswordRecoveryForUsernameDTOInterface::class, $dto);
    }
}
