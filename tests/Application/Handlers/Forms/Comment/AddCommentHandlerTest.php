<?php

namespace App\Tests\Application\Handlers\Forms\Comment;

use App\Application\Handlers\Forms\Comment\AddCommentHandler;
use App\Application\Handlers\Interfaces\Forms\Comment\AddCommentHandlerInterface;
use App\Domain\Builders\Interfaces\CommentBuilderInterface;
use App\Domain\DTO\Comment\CommentModificationDTO;
use App\Domain\DTO\Interfaces\Comment\CommentDTOInterface;
use App\Domain\Model\Category;
use App\Domain\Model\Comment;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use App\Domain\Repository\CommentRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

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

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Comment
     */
    private $comment;

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

        $this->comment = new Comment(
            'Commentaire',
            $this->trick,
            $user
        );

        $repository = $this->createMock(CommentRepository::class);

        $builder = $this->createMock(CommentBuilderInterface::class);
        $builder->method('build')->willReturnSelf();
        $builder->method('getComment')->willReturn($this->comment);

        $this->handler = new AddCommentHandler($repository, $builder);

        $dto = new CommentModificationDTO('Commentaire simulé.');
        $this->form = $this->createMock(FormInterface::class);
        $this->form->method('getData')->willReturn($dto);

        $session = new Session(new MockArraySessionStorage());
        $this->request = new Request();
        $this->request->setSession($session);
    }

    public function testConstructor()
    {
        self::assertInstanceOf(AddCommentHandlerInterface::class, $this->handler);
    }

    public function testReturnFalseIfTheFormIsNotSubmitted()
    {
        $this->form->method('isSubmitted')->willReturn(false);

        $response = $this->handler->handle($this->form, $this->trick, $this->request);

        self::assertFalse($response);
    }

    public function testReturnFalseIfTheFormIsNotValid()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(false);

        $response = $this->handler->handle($this->form, $this->trick, $this->request);

        self::assertFalse($response);
    }

    public function testReturnTrueIfTheFormIsSubmittedAndValidAndHeAddNewCom()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        $response = $this->handler->handle($this->form, $this->trick, $this->request);

        self::assertTrue($response);
    }

    public function testReturnTrueIfTheFormIsSubmittedAndValidAndHeUpdateTheCom()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        $session = $this->request->getSession();
        $session->set('comment', $this->comment);
        $this->request->setSession($session);

        $response = $this->handler->handle($this->form, $this->trick, $this->request);

        self::assertTrue($response);
    }
}
