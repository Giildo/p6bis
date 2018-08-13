<?php

namespace App\Tests\UI\Forms\Trick;

use App\Domain\DTO\Trick\TrickModificationNewPictureDTO;
use App\UI\Forms\Trick\TrickModificationNewPictureType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TrickModificationNewPictureTypeTest extends TypeTestCase
{
    public function testReturnOfTheFormType()
    {
        $form = $this->factory->create(TrickModificationNewPictureType::class);

        $file = $this->createMock(UploadedFile::class);

        $formData = [
            'description' => 'JohnDoe',
            'picture'     => $file,
        ];

        $form->submit($formData);

        $dto = $form->getData();

        self::assertInstanceOf(TrickModificationNewPictureDTO::class, $dto);
    }
}
