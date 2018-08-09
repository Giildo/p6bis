<?php

namespace App\Tests\UI\Actions\Trick;

use App\Application\Handlers\Interfaces\Forms\Trick\NewTrickHandlerInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use App\UI\Actions\Trick\NewTrickAction;
use App\UI\Presenters\Interfaces\Trick\NewTrickPresenterInterface;
use App\UI\Responders\Trick\NewTrickResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class NewTrickActionTest extends TestCase
{
    private $action;

    private $handler;

    private $request;

    protected function setUp()
    {
        $presenter = $this->createMock(NewTrickPresenterInterface::class);
        $presenter->method('newTrickPresentation')->willReturn('vue de la page');

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('url');

        $responder = new NewTrickResponder($presenter, $urlGenerator);

        $form = $this->createMock(FormInterface::class);
        $form->method('handleRequest')->willReturnSelf();
        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formFactory->method('create')->willReturn($form);

        $this->handler = $this->createMock(NewTrickHandlerInterface::class);

        $this->action = new NewTrickAction($responder, $formFactory, $this->handler);

        $this->request = $this->createMock(Request::class);
    }

    public function testConstructor()
    {
        self::assertInstanceOf(NewTrickAction::class, $this->action);
    }

    public function testRedirectionResponseIfHandlerReturnTrick()
    {
        $trick = $this->createMock(TrickInterface::class);
        $trick->method('getSlug')->willReturn('slug');

        $this->handler->method('handle')->willReturn($trick);

        $response = $this->action->newTrick($this->request);

        self::assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testResponseIfHandlerReturnNull()
    {
        $this->handler->method('handle')->willReturn(null);

        $response = $this->action->newTrick($this->request);

        self::assertInstanceOf(Response::class, $response);
        self::assertNotInstanceOf(RedirectResponse::class, $response);
    }
}
