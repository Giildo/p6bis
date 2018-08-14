<?php

namespace App\Tests\UI\Actions\Trick;

use App\Application\Handlers\Interfaces\Forms\Trick\TrickModificationHandlerInterface;
use App\Application\Helpers\PictureAndVideoTokenManager;
use App\Domain\Model\Interfaces\TrickInterface;
use App\UI\Actions\Trick\TrickModificationAction;
use App\UI\Presenters\Trick\TrickModificationPresenter;
use App\UI\Responders\Trick\TrickModificationResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Twig\Environment;

class TrickModificationActionTest extends TestCase
{
    private $request;

    private $handler;

    private $formFactory;

    private $responder;

    private $tokenManager;

    private $action;

    protected function setUp()
    {
        $form = $this->createMock(FormInterface::class);
        $form->method('handleRequest')->willReturnSelf();
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->formFactory->method('create')->willReturn($form);

        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturn('Vue de la page');
        $presenter = new TrickModificationPresenter($twig);
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/url');
        $this->responder = new TrickModificationResponder(
            $presenter,
            $urlGenerator
        );

        $this->handler = $this->createMock(TrickModificationHandlerInterface::class);

        $tokenGenerator = $this->createMock(TokenGeneratorInterface::class);
        $this->tokenManager = new PictureAndVideoTokenManager($tokenGenerator);

        $trick = $this->createMock(TrickInterface::class);
        $trick->method('getPictures')->willReturn([]);
        $trick->method('getVideos')->willReturn([]);
        $trick->method('getSlug')->willReturn('goodSlug');
        $session = new Session(new MockArraySessionStorage());
        $this->request = new Request();
        $this->request->setSession($session);
        $this->request->getSession()->set('trick', $trick);

        $this->action = new TrickModificationAction(
            $this->formFactory,
            $this->responder,
            $this->handler,
            $this->tokenManager
        );
    }

    public function testConstructor()
    {
        self::assertInstanceOf(TrickModificationAction::class, $this->action);
    }

    public function testResponseIfHandlerReturnFalse()
    {
        $this->handler->method('handle')->willReturn(false);

        $this->action = new TrickModificationAction(
            $this->formFactory,
            $this->responder,
            $this->handler,
            $this->tokenManager
        );

        $response = $this->action->modification($this->request, 'goodSlug');

        self::assertInstanceOf(Response::class, $response);
        self::assertNotInstanceOf(RedirectResponse::class, $response);
    }

    public function testRedirectResponseIfHandlerReturnTrue()
    {
        $this->handler->method('handle')->willReturn(true);

        $this->action = new TrickModificationAction(
            $this->formFactory,
            $this->responder,
            $this->handler,
            $this->tokenManager
        );

        $response = $this->action->modification($this->request, 'goodSlug');

        self::assertInstanceOf(RedirectResponse::class, $response);
    }
}
