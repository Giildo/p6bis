<?php

namespace App\Tests\UI\Forms\User;

use App\Domain\DTO\User\ProfilePictureDTO;
use App\UI\Forms\User\ProfilePictureType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProfilePictureTypeTest extends KernelTestCase
{
    public function testReturnOfTheFormType()
    {
        $kernel = self::bootKernel();

        $factory = $kernel->getContainer()->get('form.factory');
        $form = $factory->create(ProfilePictureType::class);

        $file = $this->createMock(UploadedFile::class);

        $formData = [
            'firstName'      => 'John',
            'lastName'       => 'Doe',
            'mail'           => 'john@doe.com',
            'profilePicture' => [
                'description' => 'Description de l\'image',
                'picture'     => $file,
            ],
        ];

        $form->submit($formData);

        $dto = $form->getData();

        self::assertInstanceOf(ProfilePictureDTO::class, $dto);
    }
}
