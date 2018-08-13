<?php

namespace App\Tests\Domain\Builders;

use App\Application\Helpers\SluggerHelper;
use App\Domain\Builders\PictureBuilder;
use App\Domain\DTO\Trick\NewTrickNewPictureDTO;
use App\Domain\Model\Category;
use App\Domain\Model\Picture;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use DateTime;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureBuilderTest extends TestCase
{
    public function testThePictureBuildingWithDTO()
    {
        $uploadFile = $this->createMock(UploadedFile::class);
        $uploadFile->method('guessExtension')->willReturn('jpeg');

        $slugger = new SluggerHelper();

        $category = new Category(
            $slugger->slugify('Grabs'),
            'Grabs'
        );

        $user = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john@doe.fr',
            '12345678'
        );

        $trick = new Trick(
            $slugger->slugify('Mute'),
            'mute',
            'Description de la trick',
            $category,
            $user
        );

        $dto = new NewTrickNewPictureDTO(
            'Description de l\'image',
            $uploadFile
        );

        $pictureBuilder = new PictureBuilder();

        $response = $pictureBuilder->build($dto, $trick, 1)
                                   ->getPicture();
        $datetime = new DateTime();
        $name = $datetime->format('YmdHis');

        self::assertInstanceOf(Picture::class, $response);
        self::assertEquals("mute{$name}_1", $response->getName());
    }
}
