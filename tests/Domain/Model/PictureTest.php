<?php

namespace App\Tests\Domain\Model;

use App\Domain\Model\Interfaces\PictureInterface;
use App\Domain\Model\Picture;
use PHPUnit\Framework\TestCase;

class PictureTest extends TestCase
{
    private $picture;

    public function setUp()
    {
        $this->picture = new Picture(
            'nomDeLaPhoto',
            'Description de la photo',
            'jpg',
            true
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
    }
}
