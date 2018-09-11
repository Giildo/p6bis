<?php

namespace App\Tests\UI\Actions\Trick;

use App\Domain\Repository\TrickRepository;
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
        $presenter->method('homePagePresentation')->willReturn('Vue de la page');
        $responder = new HomePageResponder($presenter);

        $repository = $this->createMock(TrickRepository::class);
        $repository->method('loadTricksWithPaging')->willReturn([]);

        $this->action = new HomePageAction($repository, $responder);
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
