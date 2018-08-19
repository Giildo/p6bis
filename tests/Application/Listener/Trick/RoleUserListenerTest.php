<?php

namespace App\Tests\Application\Listener\Trick;

use App\Application\Listener\Trick\RoleUserListener;
use App\Domain\Model\Category;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use App\Domain\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    private $repository;

    private $entityManager;

    private $urlGenerator;

    private $tokenStorage;

    private $user;

    private $request;

    private $event;

    private $checker;

    private $category;

    protected function setUp()
    {
        $this->repository = $this->createMock(TrickRepository::class);

        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->urlGenerator->method('generate')->willReturn('/goodUrl');

        $this->request = $this->createMock(Request::class);
        $this->request->attributes = $this->createMock(ParameterBagInterface::class);
        $session = new Session(new MockArraySessionStorage());
        $this->request->method('getSession')->willReturn($session);
        $this->event = $this->createMock(GetResponseEvent::class);

        $this->user = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john@doe.fr',
            '12345678'
        );

        $this->category = new Category(
            'grab',
            'Grab'
        );

        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($this->user);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->tokenStorage->method('getToken')->willReturn($token);

        $this->checker = $this->createMock(AuthorizationCheckerInterface::class);
    }

    public function testConstructor()
    {
        $listener = new RoleUserListener(
            $this->repository,
            $this->urlGenerator,
            $this->tokenStorage,
            $this->checker
        );

        self::assertInstanceOf(RoleUserListener::class, $listener);
    }

    public function testNullReturnIfTrickIsNull()
    {
        $this->request->attributes->method('get')->willReturn(null);
        $this->event->method('getRequest')->willReturn($this->request);

        $listener = new RoleUserListener(
            $this->repository,
            $this->urlGenerator,
            $this->tokenStorage,
            $this->checker
        );

        $response = $listener->onKernelRequest($this->event);

        self::assertNull($response);
    }

    public function testNullReturnIfTheURIIsntInSecureURIs()
    {
        $this->request->attributes->method('get')->willReturn('slug');
        $this->request->method('getUri')->willReturn('/badUrl');
        $this->event->method('getRequest')->willReturn($this->request);

        $listener = new RoleUserListener(
            $this->repository,
            $this->urlGenerator,
            $this->tokenStorage,
            $this->checker
        );

        $response = $listener->onKernelRequest($this->event);

        self::assertNull($response);
    }

    public function testNullReturnIfTheSlugIsWrong()
    {
        $this->request->attributes->method('get')->willReturn('slug');
        $this->request->method('getUri')->willReturn('/goodUrl');
        $this->event->method('getRequest')->willReturn($this->request);

        $this->repository->method('loadOneTrickWithCategoryAndAuthor')
                         ->willReturn(null);

        $listener = new RoleUserListener(
            $this->repository,
            $this->urlGenerator,
            $this->tokenStorage,
            $this->checker
        );

        $response = $listener->onKernelRequest($this->event);

        self::assertNull($response);
    }

    public function testNullReturnIfTheUserHasRoleUserAndIsntAuthorOfTheTrick()
    {
        $this->request->attributes->method('get')->willReturn('slug');
        $this->request->method('getUri')->willReturn('/goodUrl');
        $this->event->method('getRequest')->willReturn($this->request);

        $user = new User(
            'JaneDoe',
            'Jane',
            'Doe',
            'jane@doe.fr',
            '12345678'
        );

        $trick = new Trick(
            'mute',
            'Mute','Description de la figure',
            $this->category,
            $user
        );

        $this->repository->method('loadOneTrickWithCategoryAndAuthor')
                         ->willReturn($trick);

        $listener = new RoleUserListener(
            $this->repository,
            $this->urlGenerator,
            $this->tokenStorage,
            $this->checker
        );

        $response = $listener->onKernelRequest($this->event);

        self::assertNull($response);
    }

    public function testTrickIsInSessionIfTheUserHasRoleAdmin()
    {
        $this->request->attributes->method('get')->willReturn('slug');
        $this->request->method('getUri')->willReturn('/goodUrl');
        $this->event->method('getRequest')->willReturn($this->request);

        $user = new User(
            'JaneDoe',
            'Jane',
            'Doe',
            'jane@doe.fr',
            '12345678'
        );

        $trick = new Trick(
            'mute',
            'Mute','Description de la figure',
            $this->category,
            $user
        );

        $this->checker->method('isGranted')->willReturn(true);

        $this->repository->method('loadOneTrickWithCategoryAndAuthor')
            ->willReturn($trick);

        $listener = new RoleUserListener(
            $this->repository,
            $this->urlGenerator,
            $this->tokenStorage,
            $this->checker
        );

        $response = $listener->onKernelRequest($this->event);

        self::assertNull($response);

        $trick = $this->request->getSession()->get('trick');

        self::assertInstanceOf(TrickInterface::class, $trick);
    }

    public function testTrickIsInSessionIfTheUserHasRoleUserAndHeIsAuthorOfTheTrick()
    {
        $this->request->attributes->method('get')->willReturn('slug');
        $this->request->method('getUri')->willReturn('/goodUrl');
        $this->event->method('getRequest')->willReturn($this->request);

        $trick = new Trick(
            'mute',
            'Mute','Description de la figure',
            $this->category,
            $this->user
        );

        $this->checker->method('isGranted')->willReturn(true);

        $this->repository->method('loadOneTrickWithCategoryAndAuthor')
            ->willReturn($trick);

        $listener = new RoleUserListener(
            $this->repository,
            $this->urlGenerator,
            $this->tokenStorage,
            $this->checker
        );

        $response = $listener->onKernelRequest($this->event);

        self::assertNull($response);

        $trick = $this->request->getSession()->get('trick');

        self::assertInstanceOf(TrickInterface::class, $trick);
    }
}
