<?php

namespace App\Tests\Domain\Model;

use App\Application\Helpers\SluggerHelper;
use App\Domain\Model\Category;
use App\Domain\Model\Interfaces\PictureInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Picture;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use PHPUnit\Framework\TestCase;

class PictureTest extends TestCase
{
    private $picture;

    public function setUp()
    {
        $slugger = new SluggerHelper();

        $category = new Category(
            $slugger->slugify('Grabs'),
            'Grabs'
        );

        $author = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john@doe.com',
            '12345678'
        );

        $trick = new Trick(
            $slugger->slugify('Mute'),
            'Mute',
            'Figure de snow',
            $category,
            $author
        );

        $this->picture = new Picture(
            'nomDeLaPhoto',
            'Description de la photo',
            'jpg',
            true,
            $trick
        );
    }

    public function testConstructor()
    {
        self::assertInstanceOf(PictureInterface::class, $this->picture);
    }

    public function testGettersReturnsValueAndType()
    {
        self::assertEquals('nomDeLaPhoto', $this->picture->getName());
        self::assertInternalType('string', $this->picture->getName());

        self::assertEquals('Description de la photo', $this->picture->getDescription());
        self::assertInternalType('string', $this->picture->getDescription());

        self::assertEquals('jpg', $this->picture->getExtension());
        self::assertInternalType('string', $this->picture->getExtension());

        self::assertTrue($this->picture->isHeadPicture());
        self::assertInternalType('bool', $this->picture->isHeadPicture());

        self::assertEquals('Mute', $this->picture->getTrick()->getName());
        self::assertInstanceOf(TrickInterface::class, $this->picture->getTrick());
    }

    public function testTheModificationOfThePicture()
    {
        $this->picture->update(
            'Nouvelle description de la photo',
            false
        );

        self::assertEquals('Nouvelle description de la photo', $this->picture->getDescription());

        self::assertFalse($this->picture->isHeadPicture());
    }
}
