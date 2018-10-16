<?php

namespace App\Tests\Application\Listener\Trick;

use App\Application\Listener\Trick\RoleUserListener;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Repository\TrickRepository;
use App\Tests\Fixtures\Traits\TrickAndCategoryFixtures;
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
     * @var TrickRepository|MockObject
     */
    private $repository;

    /**
     * @var UrlGeneratorInterface|MockObject
     */
    private $urlGenerator;

    /**
     * @var TokenStorageInterface|MockObject
     */
    private $tokenStorage;

    /**
     * @var Request|MockObject
     */
    private $request;

    /**
     * @var GetResponseEvent|MockObject
     */
    private $event;

    /**
     * @var AuthorizationCheckerInterface|MockObject
     */
    private $checker;

    protected function setUp()
    {
        $this->constructCategoryAndTrick();

        $this->repository = $this->createMock(TrickRepository::class);

        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->urlGenerator->method('generate')->willReturn('/goodUrl');

        $this->request = $this->createMock(Request::class);
        $this->request->attributes = $this->createMock(ParameterBagInterface::class);
        $session = new Session(new MockArraySessionStorage());
        $this->request->method('getSession')->willReturn($session);
        $this->event = $this->createMock(GetResponseEvent::class);

        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($this->johnDoe);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->tokenStorage->method('getToken')->willReturn($token);

        $this->checker = $this->createMock(AuthorizationCheckerInterface::class);
    }

    use TrickAndCategoryFixtures;

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

        $this->repository->method('loadOneTrickWithCategoryAndAuthor')
                         ->willReturn($this->mute);

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

        $this->checker->method('isGranted')->willReturn(true);

        $this->repository->method('loadOneTrickWithCategoryAndAuthor')
            ->willReturn($this->mute);

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

        $this->checker->method('isGranted')->willReturn(true);

        $this->repository->method('loadOneTrickWithCategoryAndAuthor')
            ->willReturn($this->mute);

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
