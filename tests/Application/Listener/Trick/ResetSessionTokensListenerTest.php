<?php

namespace App\Tests\Application\Listener\Trick;

use App\Application\Listener\Trick\ResetSessionTokensListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ResetSessionTokensListenerTest extends TestCase
{
    private $listener;

    private $event;

    private $request;

    private $session;

    protected function setUp()
    {
        $tokens = [
            'token1' => 'token123456789',
            'token2' => 'tokenzduigfild',
            'token3' => 'qsdqsfgdstoken',
            'token4' => '156476545token',
        ];

        $this->session = new Session(new MockArraySessionStorage());
        $this->session->set('tokens', $tokens);

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/url');

        $this->listener = new ResetSessionTokensListener(
            $this->session,
            $urlGenerator
        );

        $this->request = $this->createMock(Request::class);

        $this->event = $this->createMock(GetResponseEvent::class);
        $this->event->method('getRequest')->willReturn($this->request);
    }

    public function testConstructor()
    {
        self::assertInstanceOf(ResetSessionTokensListener::class, $this->listener);
    }

    public function testReturnIfTheRequestIsntTheMasterRequest()
    {
        $this->event->method('isMasterRequest')->willReturn(false);

        $response = $this->listener->onKernelRequest($this->event);

        self::assertNull($response);

        self::assertEquals('token123456789', $this->session->get('tokens')['token1']);
    }

    public function testReturnNullIfTheRequestGetToWDTPath()
    {
        $this->event->method('isMasterRequest')->willReturn(true);

        $this->request->method('getUri')->willReturn('\_wdt\token');

        $response = $this->listener->onKernelRequest($this->event);

        self::assertNull($response);

        self::assertEquals('token123456789', $this->session->get('tokens')['token1']);
    }

    public function testReturnNullIfTheRequestGetToTargetedLinks()
    {
        $this->event->method('isMasterRequest')->willReturn(true);

        $this->request->method('getUri')->willReturn('/url/targeted');

        $response = $this->listener->onKernelRequest($this->event);

        self::assertNull($response);

        self::assertEquals('token123456789', $this->session->get('tokens')['token1']);
    }

    public function testResetTokensIfThePathIsntTargeted()
    {
        $this->event->method('isMasterRequest')->willReturn(true);

        $this->request->method('getUri')->willReturn('\other\target');

        $response = $this->listener->onKernelRequest($this->event);

        self::assertNull($response);

        self::assertEmpty($this->session->get('tokens'));
    }
}
