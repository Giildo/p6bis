<?php

namespace App\Tests\Domain\Model;

use App\Application\Helpers\SluggerHelper;
use App\Domain\Model\Interfaces\CategoryInterface;
use App\Domain\Model\Interfaces\PictureInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use App\Domain\Model\Category;
use App\Domain\Model\Picture;
use DateTime;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class TrickTest extends TestCase
{
    private $trick;
    private $category;
    private $author;
    private $picture;

    public function setUp()
    {
        $slugger = new SluggerHelper();

        $this->category = new Category('Grab', $slugger);
        $this->author = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john@doe.com',
            '12345678'
        );

        $this->trick = new Trick(
            "Essai d'une phrase complexe à accent !",
            'Description de la figure de snowboard.',
            $slugger,
            $this->category,
            $this->author
        );

    }

    public function testConstructor()
    {
        self::assertInstanceOf(TrickInterface::class, $this->trick);
    }

    public function testGettersReturnsValueAndType()
    {
        self::assertEquals('essai-dune-phrase-complexe-a-accent', $this->trick->getSlug());
        self::assertInternalType('string', $this->trick->getSlug());

        self::assertEquals('Essai d\'une phrase complexe à accent !', $this->trick->getName());
        self::assertInternalType('string', $this->trick->getName());

        self::assertEquals(
            'Description de la figure de snowboard.',
            $this->trick->getDescription()
        );
        self::assertInternalType('string', $this->trick->getDescription());

        self::assertFalse($this->trick->isPublished());
        self::assertInternalType('bool', $this->trick->isPublished());

        self::assertEquals((new DateTime())->format('Y-m-d H'), $this->trick->getCreatedAt()->format('Y-m-d H'));
        self::assertNull($this->trick->getUpdatedAt());

        self::assertEquals($this->category, $this->trick->getCategory());
        self::assertInstanceOf(CategoryInterface::class, $this->trick->getCategory());

        self::assertEquals($this->author, $this->trick->getAuthor());
        self::assertInstanceOf(UserInterface::class, $this->trick->getAuthor());
    }
}
