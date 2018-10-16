<?php

namespace App\Tests\Application\Listener\Comment;

use App\Application\Listener\Comment\RoleUserListener;
use App\Domain\Model\Interfaces\CommentInterface;
use App\Domain\Repository\CommentRepository;
use App\Tests\Fixtures\Traits\CommentFixtures;
use Doctrine\ORM\NonUniqueResultException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class RoleUserListenerTest extends TestCase
{
    /**
     * @var RoleUserListener
     */
    private $listener;

    /**
     * @var GetResponseEvent|MockObject
     */
    private $event;

    /**
     * @var Request|MockObject
     */
    private $request;

    /**
     * @var CommentRepository|MockObject
     */
    private $commentRepository;

    /**
     * @var TokenInterface|MockObject
     */
    private $token;

    /**
     * @var AuthorizationCheckerInterface|MockObject
     */
    private $authorizationChecker;

    protected function setUp()
    {
        $this->constructComments();

        $this->commentRepository = $this->createMock(CommentRepository::class);

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/goodUrl');

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);

        $this->token = $this->createMock(TokenInterface::class);
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->method('getToken')->willReturn($this->token);

        $this->listener = new RoleUserListener(
            $this->commentRepository,
            $urlGenerator,
            $this->authorizationChecker,
            $tokenStorage
        );

        $this->event = $this->createMock(GetResponseEvent::class);

        $this->request = $this->createMock(Request::class);
        $this->request->attributes = $this->createMock(ParameterBagInterface::class);
        $this->request->query = $this->createMock(ParameterBagInterface::class);

        $session = new Session(new MockArraySessionStorage());
        $this->request->method('getSession')->willReturn($session);
    }

    use CommentFixtures;

    /**
     * @throws NonUniqueResultException
     */
    public function testReturnNullIfNoTrickSlugAttributeInTheRequest()
    {
        $this->request->attributes->method('get')->willReturn(null);

        $this->event->method('getRequest')->willReturn($this->request);

        $this->listener->onKernelRequest($this->event);

        $session = $this->request->getSession();

        self::assertNull($session->get('comment'));
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testReturnNullIfTheUriIsWrong()
    {
        $this->request->attributes->method('get')->willReturn('trickSlug');
        $this->request->method('getUri')->willReturn('/badUrl');

        $this->event->method('getRequest')->willReturn($this->request);

        $this->listener->onKernelRequest($this->event);

        $session = $this->request->getSession();

        self::assertNull($session->get('comment'));
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testReturnNullIfTheGetDatasIsNotDefined()
    {
        $this->request->attributes->method('get')->willReturn('trickSlug');
        $this->request->method('getUri')->willReturn('/goodUrl');
        $this->request->query->method('get')->willReturn('');

        $this->event->method('getRequest')->willReturn($this->request);

        $this->listener->onKernelRequest($this->event);

        $session = $this->request->getSession();

        self::assertNull($session->get('comment'));
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testReturnNullIfTheIdInGetDatasIsWrong()
    {
        $this->request->attributes->method('get')->willReturn('trickSlug');
        $this->request->method('getUri')->willReturn('/goodUrl');
        $this->request->query->method('get')->willReturn('GETData');

        $this->commentRepository->method('loadOneCommentWithHerId')->willReturn(null);

        $this->event->method('getRequest')->willReturn($this->request);

        $this->listener->onKernelRequest($this->event);

        $session = $this->request->getSession();

        self::assertNull($session->get('comment'));
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testReturnNullIfTheUserDoesNotHaveTheRightsToModifyTheComment()
    {
        $this->request->attributes->method('get')->willReturn('trickSlug');
        $this->request->method('getUri')->willReturn('/goodUrl');
        $this->request->query->method('get')->willReturn('GETData');

        $this->commentRepository->method('loadOneCommentWithHerId')->willReturn(
            $this->comment1
        );

        $this->authorizationChecker->method('isGranted')->willReturn(false);
        $this->token->method('getUser')->willReturn($this->janeDoe);

        $this->event->method('getRequest')->willReturn($this->request);

        $this->listener->onKernelRequest($this->event);

        $session = $this->request->getSession();

        self::assertNull($session->get('comment'));
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testReturnCommentIfTheCommentIsInTheSessionIfTheUserHasTheRightsToModifyComment()
    {
        $this->request->attributes->method('get')->willReturn('trickSlug');
        $this->request->method('getUri')->willReturn('/goodUrl');
        $this->request->query->method('get')->willReturn('GETData');

        $this->commentRepository->method('loadOneCommentWithHerId')->willReturn(
            $this->comment1
        );

        $this->authorizationChecker->method('isGranted')->willReturn(true);
        $this->token->method('getUser')->willReturn($this->johnDoe);

        $this->event->method('getRequest')->willReturn($this->request);

        $this->listener->onKernelRequest($this->event);

        $session = $this->request->getSession();

        self::assertInstanceOf(CommentInterface::class, $session->get('comment'));
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testReturnNullIfTheIdInTheUriIsWrong()
    {
        $this->request->attributes->method('get')->willReturn('trickSlug');
        $this->request->method('getUri')->willReturn('/goodUrl');
        $this->request->query->method('get')->willReturn('');

        $this->commentRepository->method('loadOneCommentWithHerId')->willReturn(null);

        $this->event->method('getRequest')->willReturn($this->request);

        $this->listener->onKernelRequest($this->event);

        $session = $this->request->getSession();

        self::assertNull($session->get('comment'));
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testReturnNullIfTheUserDoesNotHaveTheRightsToDeleteTheComment()
    {
        $this->request->attributes->method('get')->willReturn('id');
        $this->request->method('getUri')->willReturn('/goodUrl');
        $this->request->query->method('get')->willReturn('');

        $this->commentRepository->method('loadOneCommentWithHerId')->willReturn(
            $this->comment1
        );

        $this->authorizationChecker->method('isGranted')->willReturn(false);
        $this->token->method('getUser')->willReturn($this->janeDoe);

        $this->event->method('getRequest')->willReturn($this->request);

        $this->listener->onKernelRequest($this->event);

        $session = $this->request->getSession();

        self::assertNull($session->get('comment'));
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testReturnNullIfTheCommentIsInTheSessionIfTheUserHasTheRightsToDeleteComment()
    {
        $this->request->attributes->method('get')->willReturn('trickSlug');
        $this->request->method('getUri')->willReturn('/goodUrl');
        $this->request->query->method('get')->willReturn('');

        $this->commentRepository->method('loadOneCommentWithHerId')->willReturn(
            $this->comment1
        );

        $this->authorizationChecker->method('isGranted')->willReturn(true);
        $this->token->method('getUser')->willReturn($this->janeDoe);

        $this->event->method('getRequest')->willReturn($this->request);

        $this->listener->onKernelRequest($this->event);

        $session = $this->request->getSession();

        self::assertInstanceOf(CommentInterface::class, $session->get('comment'));
    }
}
