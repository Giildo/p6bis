<?php

namespace App\Tests\UI\Responders\Trick;

use App\UI\Presenters\Interfaces\Trick\HomePagePresenterInterface;
use App\UI\Responders\Interfaces\Trick\HomePageResponderInterface;
use App\UI\Responders\Trick\HomePageResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class HomePageResponderTest extends TestCase
{
    private $responder;

    public function setUp()
    {
        $presenter = $this->createMock(HomePagePresenterInterface::class);
        $presenter->method('homePagePresentation')->willReturn('Vue de la page');

        $this->responder = new HomePageResponder($presenter);
    }

    public function testConstructor()
    {
        self::assertInstanceOf(HomePageResponderInterface::class, $this->responder);
    }

    public function testIfTheReturnOfPresentationIsAString()
    {
        self::assertInstanceOf(Response::class, $this->responder->homePageResponse());
    }
}
