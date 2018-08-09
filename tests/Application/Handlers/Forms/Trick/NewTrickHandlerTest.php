<?php

namespace App\Tests\Application\Handlers\Forms\Trick;

use App\Application\Handlers\Forms\Trick\NewTrickHandler;
use App\Application\Handlers\Interfaces\Forms\Trick\NewTrickHandlerInterface;
use App\Application\Helpers\PictureSaveHelper;
use App\Application\Helpers\SluggerHelper;
use App\Domain\Builders\Interfaces\PictureBuilderInterface;
use App\Domain\Builders\Interfaces\VideoBuilderInterface;
use App\Domain\Builders\TrickBuilder;
use App\Domain\DTO\Trick\NewTrickDTO;
use App\Domain\DTO\Trick\NewTrickPictureDTO;
use App\Domain\DTO\Trick\NewTrickVideoDTO;
use App\Domain\Model\Category;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Picture;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use App\Domain\Model\Video;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class NewTrickHandlerTest extends KernelTestCase
{
    private $handler;

    private $form;

    private $trick;

    private $trickBuilder;

    protected function setUp()
    {
        $kernel = self::bootKernel();

        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropSchema($entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($entityManager->getMetadataFactory()->getAllMetadata());

        $slugger = new SluggerHelper();

        $category = new Category(
            $slugger->slugify('Grabs'),
            'Grabs'
        );

        $user = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john@doe.fr',
            '12345678'
        );

        $this->trick = new Trick(
            $slugger->slugify('Mute'),
            'Mute',
            'Description de la figure',
            $category,
            $user
        );

        $pictureDTO = new NewTrickPictureDTO(
            'Description de l\'image',
            $this->createMock(UploadedFile::class)
        );

        $videoDTO = new NewTrickVideoDTO('https://www.youtube.com/watch?v=6z6KBAbM0MY');

        $trickDTO = new NewTrickDTO(
            'Indy',
            'Description de la figure',
            false,
            $category,
            [$pictureDTO],
            [$videoDTO]
        );

        $tokenInterface = $this->createMock(TokenInterface::class);
        $tokenInterface->method('getUser')->willReturn($user);
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->method('getToken')->willReturn($tokenInterface);

        $this->trickBuilder = new TrickBuilder(
            $slugger,
            $tokenStorage
        );

        $picture = new Picture(
            'mute20180802_1',
            'Description de l\'image',
            'jpeg',
            false,
            $this->trick
        );

        $pictureBuilder = $this->createMock(PictureBuilderInterface::class);
        $pictureBuilder->method('build')->willReturn($picture);
        $video = new Video('jXM-2FvU0f0', $this->trick);

        $videoBuilder = $this->createMock(VideoBuilderInterface::class);
        $videoBuilder->method('build')->willReturn($video);

        $pictureSave = new PictureSaveHelper();

        $this->handler = new NewTrickHandler(
            $this->trickBuilder,
            $pictureBuilder,
            $videoBuilder,
            $entityManager,
            $pictureSave
        );

        $this->form = $this->createMock(FormInterface::class);
        $this->form->method('getData')->willReturn($trickDTO);
    }

    public function testConstructor()
    {
        self::assertInstanceOf(NewTrickHandlerInterface::class, $this->handler);
    }

    public function testNullIfTheFormIsntSubmitted()
    {
        $this->form->method('isSubmitted')->willReturn(false);

        $response = $this->handler->handle($this->form);

        self::assertNull($response);
    }

    public function testNullIfTheFormIsSubmittedAndIsntValid()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(false);

        $response = $this->handler->handle($this->form);

        self::assertNull($response);
    }

    public function testNullIfTheTrickBuilderReturnNull()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        $response = $this->handler->handle($this->form);

        self::assertInstanceOf(TrickInterface::class, $response);
    }
}
