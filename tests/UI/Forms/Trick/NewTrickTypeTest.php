<?php

namespace App\Tests\UI\Forms\Trick;

use App\Domain\DTO\Interfaces\Trick\NewTrickDTOInterface;
use App\Domain\Model\Interfaces\PictureInterface;
use App\Domain\Model\Interfaces\VideoInterface;
use App\Tests\Fixtures\Traits\TrickAndCategoryFixtures;
use App\UI\Forms\Trick\NewTrickType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NewTrickTypeTest extends KernelTestCase
{
    public function testReturnOfTheFormType()
    {
        $this->constructCategoryAndTrick();

        $kernel = self::bootKernel();

        $factory = $kernel->getContainer()->get('form.factory');
        $form = $factory->create(NewTrickType::class);

        $pictures = [];
        $pictures[] = $this->createMock(PictureInterface::class);

        $videos = [];
        $videos[] = $this->createMock(VideoInterface::class);

        $formData = [
            'name'        => 'Mute',
            'description' => 'Description de la figure',
            'published'   => 0,
            'category'    => $this->grab,
            'pictures'    => $pictures,
            'videos'      => $videos,
        ];

        $form->submit($formData);

        $dto = $form->getData();

        self::assertInstanceOf(NewTrickDTOInterface::class, $dto);
    }

    use TrickAndCategoryFixtures;
}
