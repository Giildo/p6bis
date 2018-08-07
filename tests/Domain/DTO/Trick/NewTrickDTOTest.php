<?php

namespace App\Tests\Domain\DTO\Trick;

use App\Application\Helpers\SluggerHelper;
use App\Domain\DTO\Interfaces\Trick\NewTrickDTOInterface;
use App\Domain\DTO\Trick\NewTrickDTO;
use App\Domain\Model\Category;
use PHPUnit\Framework\TestCase;

class NewTrickDTOTest extends TestCase
{
    public function testConstructor()
    {
        $slugger = new SluggerHelper();

        $category = new Category(
            'Grab',
            $slugger
        );

        $dto = new NewTrickDTO(
            'Mute',
            'Description de la figure',
            false,
            $category,
            null,
            null
        );

        self::assertInstanceOf(NewTrickDTOInterface::class, $dto);
    }
}
