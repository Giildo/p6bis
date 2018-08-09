<?php

namespace App\Tests\UI\Forms\Trick;

use App\Domain\DTO\Interfaces\Trick\NewTrickVideoDTOInterface;
use App\UI\Forms\Trick\NewTrickVideoType;
use Symfony\Component\Form\Test\TypeTestCase;

class NewTrickVideoTypeTest extends TypeTestCase
{
    private $form;

    public function setUp()
    {
        parent::setUp();

        $this->form = $this->factory->create(NewTrickVideoType::class);
    }

    public function testReturnOfTheFormType()
    {
        $formData = [
            'url' => 'https://www.youtube.com/watch?v=F5ZTaYcfSU8',
        ];

        $this->form->submit($formData);

        $dto = $this->form->getData();

        self::assertInstanceOf(NewTrickVideoDTOInterface::class, $dto);
    }
}
