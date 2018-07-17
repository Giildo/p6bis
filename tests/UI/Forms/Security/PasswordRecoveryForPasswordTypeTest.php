<?php

namespace App\Tests\UI\Forms\Security;

use App\Domain\DTO\Interfaces\Security\PasswordRecoveryForPasswordDTOInterface;
use App\UI\Forms\Security\PasswordRecoveryForPasswordType;
use Symfony\Component\Form\Test\TypeTestCase;

class PasswordRecoveryForPasswordTypeTest extends TypeTestCase
{
    private $form;

    public function setUp()
    {
        parent::setUp();

        $this->form = $this->factory->create(PasswordRecoveryForPasswordType::class);
    }

    public function testReturnOfTheFormType()
    {
        $formData = [
            'password' => [
                'first'  => '12345678',
                'second' => '12345678',
            ],
        ];

        $this->form->submit($formData);

        $dto = $this->form->getData();

        self::assertInstanceOf(PasswordRecoveryForPasswordDTOInterface::class, $dto);
    }
}
