<?php

namespace App\Tests\Application\Listener\Security;

use App\Application\Listener\Security\NoAuthenticationCheckerListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class NoAuthenticationCheckerListenerTest extends TestCase
{
    private $listener;

    private $authorizationChecker;

    private $request;

    private $event;

    protected function setUp()
    {
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/url');

        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);

        $this->request = $this->createMock(Request::class);
        $this->event = $this->createMock(GetResponseEvent::class);
        $this->event->method('getRequest')->willReturn($this->request);

        $this->listener = new NoAuthenticationCheckerListener(
            $urlGenerator,
            $this->authorizationChecker
        );
    }

    public function testConstructor()
    {
        self::assertInstanceOf(NoAuthenticationCheckerListener::class, $this->listener);
    }

    public function testNoSettingEventResponseIfURINotIntoPathArray()
    {
        $this->request->method('getUri')->willReturn('/badUri');

        $response = $this->listener->onKernelRequest($this->event);

        self::assertNull($response);
    }

    public function testNoSettingEventResponseIfTheUserInstConnected()
    {
        $this->request->method('getUri')->willReturn('/url');
        $this->authorizationChecker->method('isGranted')->willReturn(false);

        $response = $this->listener->onKernelRequest($this->event);

        self::assertNull($response);
    }

    public function testSettingEventResponseIfTheUriIsGoodAndTheUserIsConnected()
    {
        $this->request->method('getUri')->willReturn('/url');
        $this->authorizationChecker->method('isGranted')->willReturn(true);

        $this->event->expects($this->exactly(1))
                    ->method('setResponse')
                    ->willReturn(null);

        $response = $this->listener->onKernelRequest($this->event);

        self::assertNull($response);
    }
}
