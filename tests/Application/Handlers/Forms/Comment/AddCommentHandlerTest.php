<?php

namespace App\Tests\Application\Handlers\Forms\Comment;

use App\Application\Handlers\Forms\Comment\AddCommentHandler;
use App\Domain\Builders\CommentBuilder;
use App\Domain\DTO\Comment\CommentModificationDTO;
use App\Domain\Repository\CommentRepository;
use App\Tests\Fixtures\Traits\CommentFixtures;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AddCommentHandlerTest extends TestCase
{
    /**
     * @var AddCommentHandler
     */
    private $handler;

    /**
     * @var FormInterface|MockObject
     */
    private $form;

    /**
     * @var Request
     */
    private $request;

    public function setUp()
    {
        $this->constructComments();

        $repository = $this->createMock(CommentRepository::class);

        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($this->johnDoe);
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->method('getToken')->willReturn($token);
        $commentBuilder = new CommentBuilder($tokenStorage);

        $dto = new CommentModificationDTO('Commentaire simulé.');
        $this->form = $this->createMock(FormInterface::class);
        $this->form->method('getData')->willReturn($dto);

        $session = new Session(new MockArraySessionStorage());

        $this->request = new Request();
        $this->request->setSession($session);

        $this->handler = new AddCommentHandler($repository, $commentBuilder);
    }

    use CommentFixtures;

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testReturnFalseIfTheFormIsNotSubmitted()
    {
        $this->form->method('isSubmitted')->willReturn(false);

        $response = $this->handler->handle($this->form, $this->mute, $this->request);

        self::assertFalse($response);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testReturnFalseIfTheFormIsNotValid()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(false);

        $response = $this->handler->handle($this->form, $this->mute, $this->request);

        self::assertFalse($response);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testReturnTrueIfTheFormIsSubmittedAndValidAndHeAddNewCom()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        $response = $this->handler->handle($this->form, $this->mute, $this->request);

        self::assertTrue($response);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testReturnTrueIfTheFormIsSubmittedAndValidAndHeUpdateTheCom()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        $session = $this->request->getSession();
        $session->set('comment', $this->comment1);
        $this->request->setSession($session);

        $response = $this->handler->handle($this->form, $this->mute, $this->request);

        self::assertTrue($response);
    }
}
