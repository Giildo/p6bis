<?php

namespace App\Tests\Application\Handlers\Forms\Trick;

use App\Application\Handlers\Forms\Trick\TrickModificationHandler;
use App\Application\Helpers\PictureSaveHelper;
use App\Domain\Builders\PictureBuilder;
use App\Domain\Builders\VideoBuilder;
use App\Domain\DTO\Trick\TrickModificationDTO;
use App\Domain\DTO\Trick\PictureDTO;
use App\Domain\DTO\Trick\VideoDTO;
use App\Domain\Modifier\TrickModifier;
use App\Tests\Fixtures\Traits\TrickAndCategoryFixtures;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TrickModificationHandlerTest extends TestCase
{
    /**
     * @var TrickModificationHandler
     */
    private $handler;

    /**
     * @var FormInterface|MockObject
     */
    private $form;

    protected function setUp()
    {
        $this->constructCategoryAndTrick();

        $modifier = new TrickModifier();

        $pictureBuilder = new PictureBuilder();

        $videoBuilder = new VideoBuilder();

        $pictureSave = new PictureSaveHelper();

        $entityManager = $this->createMock(EntityManagerInterface::class);

        $uploadedFile = $this->createMock(UploadedFile::class);
        $uploadedFile->method('guessExtension')->willReturn('jpeg');

        $pictureDTO = new PictureDTO(
            'Description de la nouvelle photo',
            $uploadedFile
        );

        $videoDTO = new VideoDTO('https://www.youtube.com/watch?v=B3Lhid5_HJc');

        $dto = new TrickModificationDTO(
            'Nouvelle description de la figure',
            false,
            $this->grab,
            [$pictureDTO],
            [$videoDTO]
        );

        $this->form = $this->createMock(FormInterface::class);
        $this->form->method('getData')->willReturn($dto);

        $this->handler = new TrickModificationHandler(
            $entityManager,
            $modifier,
            $pictureBuilder,
            $videoBuilder,
            $pictureSave
        );
    }

    use TrickAndCategoryFixtures;

    public function testFalseReturnIfFormIsntSubmitted()
    {
        $this->form->method('isSubmitted')->willReturn(false);

        self::assertFalse(
            $this->handler->handle(
                $this->form,
                $this->mute
            )
        );
    }

    public function testFalseReturnIfFormIsSybmittedAndIsntValid()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(false);

        self::assertFalse(
            $this->handler->handle(
                $this->form,
                $this->mute
            )
        );
    }

    public function testTrueReturnIfFormIsValidAndSubmitted()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        $response = $this->handler->handle($this->form, $this->mute);

        self::assertTrue($response);
    }
}
