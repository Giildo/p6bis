<?php

namespace App\Tests\Application\Listener\Comment;

use App\Application\Listener\Comment\RoleUserListener;
use App\Domain\Model\Category;
use App\Domain\Model\Comment;
use App\Domain\Model\Interfaces\CommentInterface;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use App\Domain\Repository\CommentRepository;
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
use Symfony\Component\Security\Core\User\UserInterface;

class RoleUserListenerTest extends TestCase
{
    private $listener;

    /**
     * @var MockObject
     */
    private $event;

    /**
     * @var MockObject
     */
    private $request;

    /**
     * @var MockObject
     */
    private $commentRepository;

    /**
     * @var CommentInterface
     */
    private $comment;

    /**
     * @var MockObject
     */
    private $token;

    /**
     * @var UserInterface
     */
    private $goodUser;

    /**
     * @var UserInterface
     */
    private $badUser;

    /**
     * @var MockObject
     */
    private $authorizationChecker;

    protected function setUp()
    {
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

        $this->goodUser = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john@doe.fr',
            '12345678'
        );

        $this->badUser = new User(
            'JaneDoe',
            'Jane',
            'Doe',
            'jane@doe.fr',
            '12345678'
        );

        $category = new Category(
            'grab',
            'Grab'
        );

        $trick = new Trick(
            'mute',
            'Mute',
            'Description de la figure',
            $category,
            $this->goodUser
        );

        $this->comment = new Comment(
            'Commentaire simulé.',
            $trick,
            $this->goodUser
        );
    }

    public function testConstructor()
    {
        self::assertInstanceOf(RoleUserListener::class, $this->listener);
    }

    public function testReturnNullIfNoTrickSlugAttributeInTheRequest()
    {
        $this->request->attributes->method('get')->willReturn(null);

        $this->event->method('getRequest')->willReturn($this->request);

        $response = $this->listener->onKernelRequest($this->event);

        self::assertNull($response);

        $session = $this->request->getSession();

        self::assertNull($session->get('comment'));
    }

    public function testReturnNullIfTheUriIsWrong()
    {
        $this->request->attributes->method('get')->willReturn('trickSlug');
        $this->request->method('getUri')->willReturn('/badUrl');

        $this->event->method('getRequest')->willReturn($this->request);

        $response = $this->listener->onKernelRequest($this->event);

        self::assertNull($response);

        $session = $this->request->getSession();

        self::assertNull($session->get('comment'));
    }

    public function testReturnNullIfTheGetDatasIsNotDefined()
    {
        $this->request->attributes->method('get')->willReturn('trickSlug');
        $this->request->method('getUri')->willReturn('/goodUrl');
        $this->request->query->method('get')->willReturn(null);

        $this->event->method('getRequest')->willReturn($this->request);

        $response = $this->listener->onKernelRequest($this->event);

        self::assertNull($response);

        $session = $this->request->getSession();

        self::assertNull($session->get('comment'));
    }

    public function testReturnNullIfTheIdInGetDatasIsWrong()
    {
        $this->request->attributes->method('get')->willReturn('trickSlug');
        $this->request->method('getUri')->willReturn('/goodUrl');
        $this->request->query->method('get')->willReturn('GETData');

        $this->commentRepository->method('loadOneCommentWithHerId')->willReturn(null);

        $this->event->method('getRequest')->willReturn($this->request);

        $response = $this->listener->onKernelRequest($this->event);

        self::assertNull($response);

        $session = $this->request->getSession();

        self::assertNull($session->get('comment'));
    }

    public function testReturnNullIfTheUserDoesNotHaveTheRightsToModifyTheComment()
    {
        $this->request->attributes->method('get')->willReturn('trickSlug');
        $this->request->method('getUri')->willReturn('/goodUrl');
        $this->request->query->method('get')->willReturn('GETData');

        $this->commentRepository->method('loadOneCommentWithHerId')->willReturn(
            $this->comment
        );

        $this->authorizationChecker->method('isGranted')->willReturn(false);
        $this->token->method('getUser')->willReturn($this->badUser);

        $this->event->method('getRequest')->willReturn($this->request);

        $response = $this->listener->onKernelRequest($this->event);

        self::assertNull($response);

        $session = $this->request->getSession();

        self::assertNull($session->get('comment'));
    }

    public function testReturnNullIfTheCommentIsInTheSessionIfTheUserHasTheRightsToModifyComment()
    {
        $this->request->attributes->method('get')->willReturn('trickSlug');
        $this->request->method('getUri')->willReturn('/goodUrl');
        $this->request->query->method('get')->willReturn('GETData');

        $this->commentRepository->method('loadOneCommentWithHerId')->willReturn(
            $this->comment
        );

        $this->authorizationChecker->method('isGranted')->willReturn(true);
        $this->token->method('getUser')->willReturn($this->badUser);

        $this->event->method('getRequest')->willReturn($this->request);

        $response = $this->listener->onKernelRequest($this->event);

        self::assertNull($response);

        $session = $this->request->getSession();

        self::assertInstanceOf(CommentInterface::class, $session->get('comment'));
    }

    public function testReturnNullIfTheIdInTheUriIsWrong()
    {
        $this->request->attributes->method('get')->willReturn('trickSlug');
        $this->request->method('getUri')->willReturn('/goodUrl');
        $this->request->query->method('get')->willReturn(null);

        $this->commentRepository->method('loadOneCommentWithHerId')->willReturn(null);

        $this->event->method('getRequest')->willReturn($this->request);

        $response = $this->listener->onKernelRequest($this->event);

        self::assertNull($response);

        $session = $this->request->getSession();

        self::assertNull($session->get('comment'));
    }

    public function testReturnNullIfTheUserDoesNotHaveTheRightsToDeleteTheComment()
    {
        $this->request->attributes->method('get')->willReturn('id');
        $this->request->method('getUri')->willReturn('/goodUrl');
        $this->request->query->method('get')->willReturn(null);

        $this->commentRepository->method('loadOneCommentWithHerId')->willReturn(
            $this->comment
        );

        $this->authorizationChecker->method('isGranted')->willReturn(false);
        $this->token->method('getUser')->willReturn($this->badUser);

        $this->event->method('getRequest')->willReturn($this->request);

        $response = $this->listener->onKernelRequest($this->event);

        self::assertNull($response);

        $session = $this->request->getSession();

        self::assertNull($session->get('comment'));
    }

    public function testReturnNullIfTheCommentIsInTheSessionIfTheUserHasTheRightsToDeleteComment()
    {
        $this->request->attributes->method('get')->willReturn('trickSlug');
        $this->request->method('getUri')->willReturn('/goodUrl');
        $this->request->query->method('get')->willReturn(null);

        $this->commentRepository->method('loadOneCommentWithHerId')->willReturn(
            $this->comment
        );

        $this->authorizationChecker->method('isGranted')->willReturn(true);
        $this->token->method('getUser')->willReturn($this->badUser);

        $this->event->method('getRequest')->willReturn($this->request);

        $response = $this->listener->onKernelRequest($this->event);

        self::assertNull($response);

        $session = $this->request->getSession();

        self::assertInstanceOf(CommentInterface::class, $session->get('comment'));
    }
}
