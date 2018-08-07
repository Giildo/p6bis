<?php

namespace App\Tests\Domain\Model;

use App\Application\Helpers\SluggerHelper;
use App\Domain\Model\Category;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Interfaces\VideoInterface;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use App\Domain\Model\Video;
use PHPUnit\Framework\TestCase;

class VideoTest extends TestCase
{
    private $video;

    public function setUp()
    {
        $slugger = new SluggerHelper();

        $category = new Category('Grabs', $slugger);

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

        $this->video = new Video(
            '6z6KBAbM0MY',
            $trick
        );
    }

    public function testConstructor()
    {
        self::assertInstanceOf(VideoInterface::class, $this->video);
    }

    public function testGettersReturnsValueAndType()
    {
        self::assertEquals('6z6KBAbM0MY', $this->video->getName());
        self::assertInternalType('string', $this->video->getName());

        self::assertEquals('Mute', $this->video->getTrick()->getName());
        self::assertInstanceOf(TrickInterface::class, $this->video->getTrick());
    }
}
