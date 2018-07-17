<?php

namespace App\Tests\UI\Actions\Security;

use App\Application\Handlers\Interfaces\Forms\Security\PasswordRecoveryForPasswordHandlerInterface;
use App\Application\Handlers\Interfaces\Forms\Security\PasswordRecoveryForUsernameHandlerInterface;
use App\Application\Mailers\Interfaces\Security\PasswordRecoveryMailerInterface;
use App\Domain\Model\User;
use App\UI\Actions\Security\PasswordRecoveryAction;
use App\UI\Presenters\Interfaces\Security\PasswordRecoveryPresenterInterface;
use App\UI\Responders\Security\PasswordRecoveryResponder;
use PHPUnit\Framework\TestCase;
use Swift_Mailer;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Environment;

class PasswordRecoveryActionTest extends TestCase
{
    private $authorizationChecker;

    private $action;

    private $request;

    private $flashBag;

    private $forPasswordHandler;

    private $forUsernameHandler;

    private $mailer;

    public function setUp()
    {
        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);

        $form = $this->createMock(FormInterface::class);
        $form->method('handleRequest')->willReturnSelf();
        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formFactory->method('create')->willReturn($form);

        $this->forUsernameHandler = $this->createMock(PasswordRecoveryForUsernameHandlerInterface::class);
        $this->forPasswordHandler = $this->createMock(PasswordRecoveryForPasswordHandlerInterface::class);

        $presenter = $this->createMock(PasswordRecoveryPresenterInterface::class);
        $presenter->method('passwordRecoveryPresentation')->willReturn('Vue de la page');
        $responder = new PasswordRecoveryResponder($presenter);

        $this->mailer = $this->createMock(PasswordRecoveryMailerInterface::class);

        $this->request = new Request();
        $this->flashBag = new FlashBag();

        $this->action = new PasswordRecoveryAction(
            $this->authorizationChecker,
            $formFactory,
            $this->forUsernameHandler,
            $this->forPasswordHandler,
            $responder,
            $this->mailer
        );
    }

    public function testConstructor()
    {
        self::assertInstanceOf(PasswordRecoveryAction::class, $this->action);
    }

    public function testRedirectResponseIfUserIsConnected()
    {
        $this->authorizationChecker->method('isGranted')->willReturn(true);

        $response = $this->action->passwordRecovery($this->request, $this->flashBag);

        self::assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testResponseIfHandleReturnNull()
    {
        $this->authorizationChecker->method('isGranted')->willReturn(false);

        $this->forUsernameHandler->method('handle')->willReturn(null);

        $response = $this->action->passwordRecovery($this->request, $this->flashBag);

        self::assertNotInstanceOf(RedirectResponse::class, $response);
        self::assertInstanceOf(Response::class, $response);
    }

    public function testResponseIfHandleReturnUserAndMailerReturnFalse()
    {
        $this->authorizationChecker->method('isGranted')->willReturn(false);

        $user = new User(
            'JohnDoe',
            'John',
            'Doe',
            'giildo.jm@gmail.com',
            '12345678'
        );

        $this->forUsernameHandler->method('handle')->willReturn($user);

        $this->mailer->method('message')->willReturn(false);

        $response = $this->action->passwordRecovery($this->request, $this->flashBag);

        self::assertNotInstanceOf(RedirectResponse::class, $response);
        self::assertInstanceOf(Response::class, $response);
    }

    public function testResponseIfHandleReturnUserAndMailerReturnTrue()
    {
        $this->authorizationChecker->method('isGranted')->willReturn(false);

        $user = new User(
            'JohnDoe',
            'John',
            'Doe',
            'giildo.jm@gmail.com',
            '12345678'
        );

        $this->forUsernameHandler->method('handle')->willReturn($user);

        $this->mailer->method('message')->willReturn(true);

        $response = $this->action->passwordRecovery($this->request, $this->flashBag);

        self::assertNotInstanceOf(RedirectResponse::class, $response);
        self::assertInstanceOf(Response::class, $response);
    }

    public function testRedirectResponseIfTokenIsIntoURIAndHandlerReturnTrue()
    {
        $this->authorizationChecker->method('isGranted')->willReturn(false);

        $this->request->query->set('ut', '8_Me185sEUfrS9W3bcsCJzEyUlyLDTg6Dn1Ul3xF0EQ');

        $this->forPasswordHandler->method('handle')->willReturn(true);

        $response = $this->action->passwordRecovery($this->request, $this->flashBag);

        self::assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testRedirectResponseIfTokenIsIntoURIButHandlerReturnFalse()
    {
        $this->authorizationChecker->method('isGranted')->willReturn(false);

        $this->request->query->set('ut', '8_Me185sEUfrS9W3bcsCJzEyUlyLDTg6Dn1Ul3xF0EQ');

        $this->forPasswordHandler->method('handle')->willReturn(false);

        $response = $this->action->passwordRecovery($this->request, $this->flashBag);

        self::assertNotInstanceOf(RedirectResponse::class, $response);
        self::assertInstanceOf(Response::class, $response);
    }
}
