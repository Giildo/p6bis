<?php

namespace App\Tests\Domain\Model;

use App\Application\Helpers\SluggerHelper;
use App\Domain\Model\Category;
use App\Domain\Model\Interfaces\CategoryInterface;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    private $category;

    public function setUp()
    {
        $slugger = new SluggerHelper('_');

        $this->category = new Category('Nom de la catégorie.', $slugger);
    }

    public function testConstructor()
    {
        self::assertInstanceOf(CategoryInterface::class, $this->category);
    }

    public function testGettersReturnsValueAndType()
    {
        self::assertEquals('Nom de la catégorie.', $this->category->getName());
        self::assertInternalType('string', $this->category->getName());

        self::assertEquals('nom_de_la_categorie', $this->category->getSlug());
        self::assertInternalType('string', $this->category->getSlug());
    }
}
