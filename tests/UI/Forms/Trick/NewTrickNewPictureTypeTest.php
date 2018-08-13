<?php

namespace App\Tests\UI\Forms\Trick;

use App\Domain\DTO\Interfaces\Trick\TrickNewPictureDTOInterface;
use App\UI\Forms\Trick\NewTrickNewPictureType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class NewTrickNewPictureTypeTest extends TypeTestCase
{
    public function testReturnOfTheFormType()
    {
        $form = $this->factory->create(NewTrickNewPictureType::class);

        $file = $this->createMock(UploadedFile::class);

        $formData = [
            'description' => 'JohnDoe',
            'picture'     => $file,
        ];

        $form->submit($formData);

        $dto = $form->getData();

        self::assertInstanceOf(TrickNewPictureDTOInterface::class, $dto);
    }
}
