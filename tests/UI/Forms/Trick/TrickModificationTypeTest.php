<?php

namespace App\Tests\UI\Forms\Trick;

use App\Domain\DTO\Interfaces\Trick\TrickModificationDTOInterface;
use App\Domain\Model\Interfaces\PictureInterface;
use App\Domain\Model\Interfaces\VideoInterface;
use App\Tests\Fixtures\Traits\TrickAndCategoryFixtures;
use App\UI\Forms\Trick\TrickModificationType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TrickModificationTypeTest extends KernelTestCase
{
    public function testReturnOfTheFormType()
    {
        $this->constructCategoryAndTrick();

        $kernel = self::bootKernel();

        $factory = $kernel->getContainer()->get('form.factory');
        $form = $factory->create(TrickModificationType::class);

        $pictures = [];
        $pictures[] = $this->createMock(PictureInterface::class);

        $videos = [];
        $videos[] = $this->createMock(VideoInterface::class);

        $formData = [
            'description' => 'Description de la figure',
            'published'   => 0,
            'category'    => $this->grab,
            'pictures'    => $pictures,
            'videos'      => $videos,
        ];

        $form->submit($formData);

        $dto = $form->getData();

        self::assertInstanceOf(TrickModificationDTOInterface::class, $dto);
    }

    use TrickAndCategoryFixtures;
}
