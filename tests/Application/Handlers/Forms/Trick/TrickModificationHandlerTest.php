<?php

namespace App\Tests\Application\Handlers\Forms\Trick;

use App\Application\Handlers\Forms\Trick\TrickModificationHandler;
use App\Application\Handlers\Interfaces\Forms\Trick\TrickModificationHandlerInterface;
use App\Application\Helpers\PictureSaveHelper;
use App\Domain\Builders\PictureBuilder;
use App\Domain\Builders\VideoBuilder;
use App\Domain\DTO\Trick\NewTrickNewPictureDTO;
use App\Domain\DTO\Trick\NewTrickNewVideoDTO;
use App\Domain\DTO\Trick\TrickModificationDTO;
use App\Domain\DTO\Trick\TrickModificationNewPictureDTO;
use App\Domain\DTO\Trick\TrickModificationNewVideoDTO;
use App\Domain\Model\Category;
use App\Domain\Model\Interfaces\PictureInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Interfaces\VideoInterface;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use App\Domain\Modifier\TrickModifier;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TrickModificationHandlerTest extends KernelTestCase
{
    private $handler;

    private $form;

    private $trick;

    private $repository;

    protected function setUp()
    {
        $kernel = self::bootKernel();

        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->repository = $entityManager->getRepository(Trick::class);

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropSchema($entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($entityManager->getMetadataFactory()->getAllMetadata());

        $modifier = new TrickModifier();

        $pictureBuilder = new PictureBuilder();

        $videoBuilder = new VideoBuilder();

        $pictureSave = new PictureSaveHelper();

        $this->handler = new TrickModificationHandler(
            $entityManager,
            $modifier,
            $pictureBuilder,
            $videoBuilder,
            $pictureSave
        );

        $category = new Category(
            'grab',
            'Grab'
        );

        $author = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john@doe.fr',
            '12345678'
        );

        $this->trick = new Trick(
            'mute',
            'Mute',
            'Description de la figure Mute',
            $category,
            $author
        );

        $uploadedFile = $this->createMock(UploadedFile::class);
        $uploadedFile->method('guessExtension')->willReturn('jpeg');

        $pictureDTO = new TrickModificationNewPictureDTO(
            'Description de la nouvelle photo',
            $uploadedFile
        );

        $videoDTO = new TrickModificationNewVideoDTO('https://www.youtube.com/watch?v=B3Lhid5_HJc');

        $dto = new TrickModificationDTO(
            'Nouvelle description de la figure',
            false,
            $category,
            [$pictureDTO],
            [$videoDTO]
        );
        $this->form = $this->createMock(FormInterface::class);
        $this->form->method('getData')->willReturn($dto);
    }

    public function testConstructor()
    {
        self::assertInstanceOf(TrickModificationHandlerInterface::class, $this->handler);
    }

    public function testFalseReturnIfFormIsntSubmitted()
    {
        $this->form->method('isSubmitted')->willReturn(false);

        $response = $this->handler->handle($this->form, $this->trick);

        self::assertFalse($response);
    }

    public function testFalseReturnIfFormIsSybmittedAndIsntValid()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(false);

        $response = $this->handler->handle($this->form, $this->trick);

        self::assertFalse($response);
    }

    public function testTrueReturnIfFormIsValidAndSubmitted()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        $response = $this->handler->handle($this->form, $this->trick);

        self::assertTrue($response);

        $trick = $this->repository->loadOneTrickWithCategoryAndAuthor($this->trick->getSlug());

        self::assertInstanceOf(TrickInterface::class, $trick);
        self::assertEquals('Nouvelle description de la figure', $trick->getDescription());
        self::assertInstanceOf(PictureInterface::class, $trick->getPictures()[0]);
        self::assertEquals('Description de la nouvelle photo', $trick->getPictures()[0]->getDescription());
        self::assertInstanceOf(VideoInterface::class, $trick->getVideos()[0]);
        self::assertEquals('B3Lhid5_HJc', $trick->getVideos()[0]->getName());
    }
}
