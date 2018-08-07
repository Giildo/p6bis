<?php

namespace App\Tests\UI\Forms\Trick;

use App\Domain\DTO\Interfaces\Trick\NewTrickPictureDTOInterface;
use App\UI\Forms\Trick\NewTrickPictureType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class NewTrickPictureTypeTest extends TypeTestCase
{
    private $form;

    public function setUp()
    {
        parent::setUp();

        $this->form = $this->factory->create(NewTrickPictureType::class);
    }

    public function testReturnOfTheFormType()
    {
        $file = $this->createMock(UploadedFile::class);

        $formData = [
            'description' => 'JohnDoe',
            'picture'     => $file,
        ];

        $this->form->submit($formData);

        $dto = $this->form->getData();

        self::assertInstanceOf(NewTrickPictureDTOInterface::class, $dto);
    }
}
