<?php

namespace App\Tests\UI\Actions\Security;

use App\Application\Handlers\Interfaces\Forms\Security\PasswordRecoveryForPasswordHandlerInterface;
use App\UI\Actions\Security\PasswordRecoveryPasswordAction;
use App\UI\Presenters\Interfaces\Security\PasswordRecoveryPresenterInterface;
use App\UI\Responders\Security\PasswordRecoveryResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class PasswordRecoveryPasswordActionTest extends TestCase
{
    private $authorizationChecker;

    private $action;

    private $request;

    private $flashBag;

    private $forPasswordHandler;

    public function setUp()
    {
        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);

        $form = $this->createMock(FormInterface::class);
        $form->method('handleRequest')->willReturnSelf();
        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formFactory->method('create')->willReturn($form);

        $this->forPasswordHandler = $this->createMock(PasswordRecoveryForPasswordHandlerInterface::class);

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('url');

        $presenter = $this->createMock(PasswordRecoveryPresenterInterface::class);
        $presenter->method('passwordRecoveryPresentation')->willReturn('Vue de la page');
        $responder = new PasswordRecoveryResponder($presenter, $urlGenerator);

        $this->request = new Request();
        $this->flashBag = new FlashBag();

        $this->action = new PasswordRecoveryPasswordAction(
            $this->authorizationChecker,
            $formFactory,
            $this->forPasswordHandler,
            $responder
        );
    }

    public function testConstructor()
    {
        self::assertInstanceOf(PasswordRecoveryPasswordAction::class, $this->action);
    }

    public function testRedirectResponseIfUserIsConnected()
    {
        $this->authorizationChecker->method('isGranted')->willReturn(true);

        $response = $this->action->passwordRecovery($this->request, $this->flashBag);

        self::assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testRedirectResponseIfHandlerReturnTrue()
    {
        $this->authorizationChecker->method('isGranted')->willReturn(false);

        $this->forPasswordHandler->method('handle')->willReturn(true);

        $response = $this->action->passwordRecovery($this->request, $this->flashBag);

        self::assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testRedirectResponseIfHandlerReturnFalse()
    {
        $this->authorizationChecker->method('isGranted')->willReturn(false);

        $this->forPasswordHandler->method('handle')->willReturn(false);

        $response = $this->action->passwordRecovery($this->request, $this->flashBag);

        self::assertNotInstanceOf(RedirectResponse::class, $response);
        self::assertInstanceOf(Response::class, $response);
    }
}
