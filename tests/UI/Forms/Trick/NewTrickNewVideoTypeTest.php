<?php

namespace App\Tests\UI\Forms\Trick;

use App\Domain\DTO\Interfaces\Trick\VideoDTOInterface;
use App\UI\Forms\Trick\NewTrickNewVideoType;
use Symfony\Component\Form\Test\TypeTestCase;

class NewTrickNewVideoTypeTest extends TypeTestCase
{
    public function testReturnOfTheFormType()
    {
        $form = $this->factory->create(NewTrickNewVideoType::class);

        $formData = [
            'url' => 'https://www.youtube.com/watch?v=F5ZTaYcfSU8',
        ];

        $form->submit($formData);

        $dto = $form->getData();

        self::assertInstanceOf(VideoDTOInterface::class, $dto);
    }
}
