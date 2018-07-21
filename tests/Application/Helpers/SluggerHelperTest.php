<?php

namespace App\Tests\Application\Helpers;

use App\Application\Helpers\Interfaces\SluggerHelperInterface;
use App\Application\Helpers\SluggerHelper;
use PHPUnit\Framework\TestCase;

class SluggerHelperTest extends TestCase
{
    private $slugger;

    public function setUp()
    {
        $this->slugger = new SluggerHelper();
    }

    public function testConstructor()
    {
        self::assertInstanceOf(SluggerHelperInterface::class, $this->slugger);
    }

    public function testSetterForSpaceCharacter()
    {
        self::assertEquals('-', $this->slugger->getSpaceCharacter());
        $this->slugger->setSpaceCharacter('_');
        self::assertEquals('_', $this->slugger->getSpaceCharacter());
    }
}
