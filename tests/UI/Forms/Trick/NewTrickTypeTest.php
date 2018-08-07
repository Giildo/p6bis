<?php

namespace App\Tests\UI\Forms\Trick;

use App\Domain\DTO\Interfaces\Trick\NewTrickDTOInterface;
use App\Domain\Model\Interfaces\CategoryInterface;
use App\Domain\Model\Interfaces\PictureInterface;
use App\Domain\Model\Interfaces\VideoInterface;
use App\UI\Forms\Trick\NewTrickType;
use Symfony\Component\Form\Test\TypeTestCase;

class NewTrickTypeTest extends TypeTestCase
{
    private $form;

    public function setUp()
    {
        parent::setUp();

        $this->form = $this->factory->create(NewTrickType::class);
    }

    public function testReturnOfTheFormType()
    {
        $category = $this->createMock(CategoryInterface::class);

        $pictures = [];
        $pictures[] = $this->createMock(PictureInterface::class);

        $videos = [];
        $videos[] = $this->createMock(VideoInterface::class);

        $formData = [
            'name'        => 'Mute',
            'description' => 'Description de la figure',
            'published'   => 0,
            'category'    => $category,
            'pictures'    => $pictures,
            'videos'      => $videos,
        ];

        $this->form->submit($formData);

        $dto = $this->form->getData();

        self::assertInstanceOf(NewTrickDTOInterface::class, $dto);
    }
}
