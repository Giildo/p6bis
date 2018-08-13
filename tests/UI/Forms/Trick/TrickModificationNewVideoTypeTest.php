<?php

namespace App\Tests\UI\Forms\Trick;

use App\Domain\DTO\Trick\TrickModificationNewVideoDTO;
use App\UI\Forms\Trick\TrickModificationNewVideoType;
use Symfony\Component\Form\Test\TypeTestCase;

class TrickModificationNewVideoTypeTest extends TypeTestCase
{
    public function testReturnOfTheFormType()
    {
        $form = $this->factory->create(TrickModificationNewVideoType::class);

        $formData = [
            'url' => 'https://www.youtube.com/watch?v=F5ZTaYcfSU8',
        ];

        $form->submit($formData);

        $dto = $form->getData();

        self::assertInstanceOf(TrickModificationNewVideoDTO::class, $dto);
    }
}
