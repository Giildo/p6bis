<?php

namespace App\Tests\UI\Responders\Trick;

use App\Domain\Model\Trick;
use App\UI\Presenters\Interfaces\Trick\ShowTrickPresenterInterface;
use App\UI\Responders\Interfaces\Trick\ShowTrickResponderInterface;
use App\UI\Responders\Trick\ShowTrickResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ShowTrickResponderTest extends TestCase
{
    private $responder;

    public function setUp()
    {
        $presenter = $this->createMock(ShowTrickPresenterInterface::class);
        $presenter->method('showTrickPresentation')->willReturn('Vue de la page');

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('url');

        $this->responder = new ShowTrickResponder($presenter, $urlGenerator);
    }

    public function testConstructor()
    {
        self::assertInstanceOf(ShowTrickResponderInterface::class, $this->responder);
    }

    public function testTheResponseReturn()
    {
        $trick = $this->createMock(Trick::class);
        $pictures = [];
        $videos = [];

        $response = $this->responder->showTrickResponse(false, $trick, $pictures, $videos);

        self::assertInstanceOf(Response::class, $response);
        self::assertNotInstanceOf(RedirectResponse::class, $response);
    }

    public function testTheRedirectResponseReturn()
    {
        $response = $this->responder->showTrickResponse();

        self::assertInstanceOf(RedirectResponse::class, $response);
    }
}
