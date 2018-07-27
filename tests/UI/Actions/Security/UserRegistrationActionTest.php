<?php

namespace App\Tests\UI\Actions\Security;

use App\Application\Handlers\Interfaces\Forms\Security\UserRegistrationHandlerInterface;
use App\UI\Actions\Security\UserRegistrationAction;
use App\UI\Presenters\Interfaces\Security\UserRegistrationPresenterInterface;
use App\UI\Responders\Security\UserRegistrationResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserRegistrationActionTest extends TestCase
{
    private $userRegistrationAction;

    private $authorizationChecker;

    private $handler;

    private $request;

    protected function setUp()
    {
        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);

        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formInterface = $this->createMock(FormInterface::class);
        $formFactory->method('create')->willReturn($formInterface);
        $formInterface->method('handleRequest')->willReturnSelf();

        $this->handler = $this->createMock(UserRegistrationHandlerInterface::class);

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('url');

        $presenter = $this->createMock(UserRegistrationPresenterInterface::class);
        $responder = new UserRegistrationResponder($presenter, $urlGenerator);

        $this->request = $this->createMock(Request::class);

        $this->userRegistrationAction = new UserRegistrationAction(
            $formFactory,
            $this->handler,
            $responder,
            $this->authorizationChecker
        );
    }

    public function testConstructor()
    {
        self::assertInstanceOf(UserRegistrationAction::class, $this->userRegistrationAction);
    }

    public function testRedirectionIfUserIsConnected()
    {
        $this->authorizationChecker->method('isGranted')->willReturn(true);

        $response = $this->userRegistrationAction->registration($this->request);

        self::assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testRedirectionIfHandlerIsTrue()
    {
        $this->authorizationChecker->method('isGranted')->willReturn(false);

        $this->handler->method('handle')->willReturn(true);

        $response = $this->userRegistrationAction->registration($this->request);

        self::assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testNotRedirectionIfHandlerIsFalse()
    {
        $this->authorizationChecker->method('isGranted')->willReturn(false);

        $this->handler->method('handle')->willReturn(false);

        $response = $this->userRegistrationAction->registration($this->request);

        self::assertInstanceOf(Response::class, $response);
        self::assertNotInstanceOf(RedirectResponse::class, $response);
    }
}
