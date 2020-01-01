<?php

namespace App\Tests\Application\Handlers\Forms\User;

use App\Application\Handlers\Forms\User\ProfilePictureHandler;
use App\Application\Helpers\PictureSaveHelper;
use App\Domain\Builders\PictureBuilder;
use App\Domain\DTO\Trick\NewPictureDTO;
use App\Domain\DTO\User\ProfilePictureDTO;
use App\Domain\Repository\UserRepository;
use App\Tests\Fixtures\Traits\PictureAndVideoFixtures;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProfilePictureHandlerTest extends TestCase
{
    /**
     * @var FormInterface|MockObject
     */
    private $form;

    /**
     * @var ProfilePictureHandler
     */
    private $handler;

    public function setUp()
    {
        $this->constructPicturesAndVideos();

        $pictureBuilder = new PictureBuilder();

        $userRepository = $this->createMock(UserRepository::class);

        $pictureSaverHelper = new PictureSaveHelper();

        $uploadFile = $this->createMock(UploadedFile::class);
        $uploadFile->method('guessExtension')->willReturn('jpeg');
        $pictureDTO = new NewPictureDTO(
            'Description de l\'image',
            $uploadFile
        );

        $profilePictureDTO = new ProfilePictureDTO(
            'John',
            'Doe',
            'john@doe.com',
            $pictureDTO
        );
        $this->form = $this->createMock(FormInterface::class);
        $this->form->method('getData')->willReturn($profilePictureDTO);

        $file = fopen(__DIR__ . '/../../../../../public/pic/users/ProfilePicture123456789.jpeg', 'w+');
        fclose($file);

        $this->handler = new ProfilePictureHandler(
            $pictureBuilder,
            $userRepository,
            $pictureSaverHelper
        );
    }

    use PictureAndVideoFixtures;

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testFalseReturnIfFormIsntSubmitted()
    {
        $this->form->method('isSubmitted')->willReturn(false);

        self::assertFalse($this->handler->handle($this->form, $this->johnDoe));
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testFalseReturnIfFormIsntValid()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(false);

        self::assertFalse($this->handler->handle($this->form, $this->johnDoe));
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testTrueReturnIfFormIsSubmittedAndValid()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        self::assertTrue($this->handler->handle($this->form, $this->johnDoe));
    }
}
