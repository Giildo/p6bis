<?php

namespace App\Tests\Domain\Builders;

use App\Application\Helpers\SluggerHelper;
use App\Domain\Builders\VideoBuilder;
use App\Domain\DTO\Trick\NewTrickVideoDTO;
use App\Domain\Model\Category;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use App\Domain\Model\Video;
use PHPUnit\Framework\TestCase;

class VideoBuilderTest extends TestCase
{
    public function testTheVideoBuildingWithDTO()
    {
        $dto = new NewTrickVideoDTO('https://www.youtube.com/watch?v=ud_gsZqO_gk');

        $slugger = new SluggerHelper();

        $category = new Category(
            $slugger->slugify('Grab'),
            'Grab'
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

        $builder = new VideoBuilder();
        $response = $builder->build($dto, $trick)
                            ->getVideo();

        self::assertInstanceOf(Video::class, $response);
        self::assertEquals('ud_gsZqO_gk', $response->getName());
    }
}
