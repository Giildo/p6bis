<?php

namespace App\Tests\UI\Actions\Trick;

use App\UI\Actions\Trick\HomePageAction;
use App\UI\Presenters\Interfaces\Trick\HomePagePresenterInterface;
use App\UI\Responders\Trick\HomePageResponder;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomePageActionTest extends TestCase
{
    private $action;

    public function setUp()
    {
        $presenter = $this->createMock(HomePagePresenterInterface::class);
        $presenter->method('homePageResponse')->willReturn('Vue de la page');
        $responder = new HomePageResponder($presenter);

        $entityManager = $this->createMock(EntityManagerInterface::class);

        $this->action = new HomePageAction($entityManager, $responder);
    }

    public function testConstructor()
    {
        self::assertInstanceOf(HomePageAction::class, $this->action);
    }

    public function testTheReturnOfActionIsResponse()
    {
        $request = $this->createMock(Request::class);

        self::assertInstanceOf(Response::class, $this->action->homePage($request));
    }
}
