<?php

namespace App\Tests\Application\Handlers\Forms\Trick;

use App\Application\Handlers\Forms\Trick\NewTrickHandler;
use App\Application\Helpers\PictureSaveHelper;
use App\Application\Helpers\SluggerHelper;
use App\Domain\Builders\Interfaces\PictureBuilderInterface;
use App\Domain\Builders\Interfaces\VideoBuilderInterface;
use App\Domain\Builders\TrickBuilder;
use App\Domain\DTO\Interfaces\Trick\PictureDTOInterface;
use App\Domain\DTO\Interfaces\Trick\VideoDTOInterface;
use App\Domain\DTO\Trick\NewTrickDTO;
use App\Domain\DTO\Trick\NewPictureDTO;
use App\Domain\DTO\Trick\NewVideoDTO;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Tests\Fixtures\Traits\PictureAndVideoFixtures;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class NewTrickHandlerTest extends TestCase
{
    /**
     * @var NewTrickHandler
     */
    private $handler;

    /**
     * @var FormInterface|MockObject
     */
    private $form;

    /**
     * @var TrickBuilder
     */
    private $trickBuilder;

    /**
     * @var PictureDTOInterface
     */
    private $pictureDTO;

    /**
     * @var VideoDTOInterface
     */
    private $videoDTO;

    protected function setUp()
    {
        $this->constructPicturesAndVideos();

        $slugger = new SluggerHelper();

        $pictureDTO = new NewPictureDTO(
            'Description de l\'image',
            $this->createMock(UploadedFile::class)
        );

        $videoDTO = new NewVideoDTO('https://www.youtube.com/watch?v=6z6KBAbM0MY');

        $trickDTO = new NewTrickDTO(
            'Indy',
            'Description de la figure',
            false,
            $this->grab,
            [$pictureDTO],
            [$videoDTO],
            $pictureDTO
        );

        $this->form = $this->createMock(FormInterface::class);
        $this->form->method('getData')->willReturn($trickDTO);

        $tokenInterface = $this->createMock(TokenInterface::class);
        $tokenInterface->method('getUser')->willReturn($this->johnDoe);
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->method('getToken')->willReturn($tokenInterface);

        $this->trickBuilder = new TrickBuilder(
            $slugger,
            $tokenStorage
        );

        $pictureBuilder = $this->createMock(PictureBuilderInterface::class);
        $pictureBuilder->method('build')->willReturnSelf();
        $pictureBuilder->method('getPicture')->willReturn($this->pictureNoHead);

        $videoBuilder = $this->createMock(VideoBuilderInterface::class);
        $videoBuilder->method('build')->willReturnSelf();
        $videoBuilder->method('getVideo')->willReturn($this->videoTrick);

        $pictureSave = new PictureSaveHelper();

        $entityManager = $this->createMock(EntityManagerInterface::class);

        $this->handler = new NewTrickHandler(
            $this->trickBuilder,
            $pictureBuilder,
            $videoBuilder,
            $entityManager,
            $pictureSave
        );
    }

    use PictureAndVideoFixtures;

    public function testNullIfTheFormIsntSubmitted()
    {
        $this->form->method('isSubmitted')->willReturn(false);

        self::assertNull($this->handler->handle($this->form));
    }

    public function testNullIfTheFormIsSubmittedAndIsntValid()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(false);

        self::assertNull($this->handler->handle($this->form));
    }

    public function testBuilderReturnTrick()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        self::assertInstanceOf(TrickInterface::class, $this->handler->handle($this->form));
    }
}
