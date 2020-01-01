<?php

namespace App\Tests\Application\Listener\Trick;

use App\Application\Listener\Trick\ResetSessionTokensListener;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ResetSessionTokensListenerTest extends TestCase
{
    /**
     * @var ResetSessionTokensListener
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
     * @var Session
     */
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
            $urlGenerator
        );

        $this->request = $this->createMock(Request::class);
        $this->request->method('getSession')->willReturn($this->session);

        $this->event = $this->createMock(GetResponseEvent::class);
        $this->event->method('getRequest')->willReturn($this->request);
    }

    public function testReturnIfTheRequestIsntTheMasterRequest()
    {
        $this->event->method('isMasterRequest')->willReturn(false);

        $this->listener->onKernelRequest($this->event);

        self::assertEquals('token123456789', $this->session->get('tokens')['token1']);
    }

    public function testReturnNullIfTheRequestGetToWDTPath()
    {
        $this->event->method('isMasterRequest')->willReturn(true);

        $this->request->method('getUri')->willReturn('\_wdt\token');

        $this->listener->onKernelRequest($this->event);

        self::assertEquals('token123456789', $this->session->get('tokens')['token1']);
    }

    public function testReturnNullIfTheRequestGetToTargetedLinks()
    {
        $this->event->method('isMasterRequest')->willReturn(true);

        $this->request->method('getUri')->willReturn('/url/targeted');

        $this->listener->onKernelRequest($this->event);

        self::assertEquals('token123456789', $this->session->get('tokens')['token1']);
    }

    public function testResetTokensIfThePathIsntTargeted()
    {
        $this->event->method('isMasterRequest')->willReturn(true);

        $this->request->method('getUri')->willReturn('\other\target');

        $this->listener->onKernelRequest($this->event);

        self::assertEmpty($this->session->get('tokens'));
    }
}
