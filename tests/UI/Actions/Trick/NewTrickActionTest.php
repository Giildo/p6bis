<?php

namespace App\Tests\UI\Actions\Trick;

use App\Application\Handlers\Interfaces\Forms\Trick\NewTrickHandlerInterface;
use App\Tests\Fixtures\Traits\TrickAndCategoryFixtures;
use App\UI\Actions\Trick\NewTrickAction;
use App\UI\Presenters\Trick\NewTrickPresenter;
use App\UI\Responders\Trick\NewTrickResponder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

class NewTrickActionTest extends TestCase
{
    /**
     * @var NewTrickAction
     */
    private $action;

    /**
     * @var NewTrickHandlerInterface|MockObject
     */
    private $handler;

    /**
     * @var Request
     */
    private $request;

    protected function setUp()
    {
        $this->constructCategoryAndTrick();

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/url');

        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturn('vue de la page');
        $presenter = new NewTrickPresenter($twig);
        $responder = new NewTrickResponder($presenter, $urlGenerator);

        $form = $this->createMock(FormInterface::class);
        $form->method('handleRequest')->willReturnSelf();
        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formFactory->method('create')->willReturn($form);

        $this->handler = $this->createMock(NewTrickHandlerInterface::class);

        $this->request = new Request();

        $this->action = new NewTrickAction(
            $responder,
            $formFactory,
            $this->handler
        );
    }

    use TrickAndCategoryFixtures;

    /**
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function testRedirectionResponseIfHandlerReturnTrick()
    {
        $this->handler->method('handle')->willReturn($this->mute);

        self::assertInstanceOf(
            RedirectResponse::class,
            $this->action->newTrick($this->request)
        );
    }

    /**
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function testResponseIfHandlerReturnNull()
    {
        $this->handler->method('handle')->willReturn(null);

        $response = $this->action->newTrick($this->request);

        self::assertInstanceOf(Response::class, $response);
        self::assertNotInstanceOf(RedirectResponse::class, $response);
    }
}
