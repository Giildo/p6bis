<?php

namespace App\Tests\Application\Handlers\Forms\Comment;

use App\Application\Handlers\Forms\Comment\AddCommentHandler;
use App\Application\Handlers\Interfaces\Forms\Comment\AddCommentHandlerInterface;
use App\Domain\Builders\Interfaces\CommentBuilderInterface;
use App\Domain\DTO\Interfaces\Comment\AddCommentDTOInterface;
use App\Domain\Model\Category;
use App\Domain\Model\Comment;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use App\Domain\Repository\CommentRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;

class AddCommentHandlerTest extends TestCase
{
    private $handler;

    /**
     * @var Trick
     */
    private $trick;

    /**
     * @var MockObject
     */
    private $form;

    public function setUp()
    {
        $user = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john@doe.fr',
            '12345678'
        );

        $category = new Category(
            'grab',
            'Grab'
        );

        $this->trick = new Trick(
            'mute',
            'Mute',
            'Description de la figure',
            $category,
            $user
        );

        $comment = new Comment(
            'Commentaire',
            $this->trick,
            $user
        );

        $repository = $this->createMock(CommentRepository::class);

        $builder = $this->createMock(CommentBuilderInterface::class);
        $builder->method('build')->willReturnSelf();
        $builder->method('getComment')->willReturn($comment);

        $this->handler = new AddCommentHandler($repository, $builder);

        $dto = $this->createMock(AddCommentDTOInterface::class);
        $this->form = $this->createMock(FormInterface::class);
        $this->form->method('getData')->willReturn($dto);
    }

    public function testConstructor()
    {
        self::assertInstanceOf(AddCommentHandlerInterface::class, $this->handler);
    }

    public function testReturnFalseIfTheFormIsNotSubmitted()
    {
        $this->form->method('isSubmitted')->willReturn(false);

        $response = $this->handler->handle($this->form, $this->trick);

        self::assertFalse($response);
    }

    public function testReturnFalseIfTheFormIsNotValid()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(false);

        $response = $this->handler->handle($this->form, $this->trick);

        self::assertFalse($response);
    }

    public function testReturnTrueIfTheFormIsSubmittedAndValid()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        $response = $this->handler->handle($this->form, $this->trick);

        self::assertTrue($response);
    }
}
