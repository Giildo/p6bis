<?php

namespace App\Tests\UI\Actions\Security;

use App\Application\Handlers\Interfaces\Forms\Security\PasswordRecoveryForPasswordHandlerInterface;
use App\Application\Handlers\Interfaces\Forms\Security\PasswordRecoveryForUsernameHandlerInterface;
use App\Application\Mailers\Interfaces\Security\PasswordRecoveryMailerInterface;
use App\Tests\Fixtures\Traits\UsersFixtures;
use App\UI\Actions\Security\PasswordRecoveryUsernameAction;
use App\UI\Presenters\Security\PasswordRecoveryPresenter;
use App\UI\Responders\Security\PasswordRecoveryResponder;
use Doctrine\ORM\ORMException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

class PasswordRecoveryUsernameActionTest extends TestCase
{
    /**
     * @var PasswordRecoveryUsernameAction
     */
    private $action;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var FlashBag
     */
    private $flashBag;

    /**
     * @var PasswordRecoveryForPasswordHandlerInterface|MockObject
     */
    private $forPasswordHandler;

    /**
     * @var PasswordRecoveryForUsernameHandlerInterface|MockObject
     */
    private $forUsernameHandler;

    /**
     * @var PasswordRecoveryMailerInterface|MockObject
     */
    private $mailer;

    public function setUp()
    {
        $this->constructUsers();

        $form = $this->createMock(FormInterface::class);
        $form->method('handleRequest')->willReturnSelf();
        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formFactory->method('create')->willReturn($form);

        $this->forUsernameHandler = $this->createMock(PasswordRecoveryForUsernameHandlerInterface::class);
        $this->forPasswordHandler = $this->createMock(PasswordRecoveryForPasswordHandlerInterface::class);

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/url');

        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturn('vue de la page');
        $presenter = new PasswordRecoveryPresenter($twig);
        $responder = new PasswordRecoveryResponder($presenter, $urlGenerator);

        $this->mailer = $this->createMock(PasswordRecoveryMailerInterface::class);

        $this->request = new Request();
        $this->flashBag = new FlashBag();

        $this->action = new PasswordRecoveryUsernameAction(
            $formFactory, $this->forUsernameHandler, $responder, $this->mailer
        );
    }

    use UsersFixtures;

    /**
     * @throws ORMException
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function testResponseIfHandleReturnNull()
    {
        $this->forUsernameHandler->method('handle')->willReturn(null);

        $response = $this->action->passwordRecovery($this->request);

        self::assertNotInstanceOf(RedirectResponse::class, $response);
        self::assertInstanceOf(Response::class, $response);
    }

    /**
     * @throws ORMException
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function testResponseIfHandleReturnUserAndMailerReturnFalse()
    {
        $this->forUsernameHandler->method('handle')->willReturn($this->johnDoe);

        $this->mailer->method('message')->willReturn(false);

        $response = $this->action->passwordRecovery($this->request);

        self::assertNotInstanceOf(RedirectResponse::class, $response);
        self::assertInstanceOf(Response::class, $response);
    }

    /**
     * @throws ORMException
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function testResponseIfHandleReturnUserAndMailerReturnTrue()
    {
        $this->forUsernameHandler->method('handle')->willReturn($this->johnDoe);

        $this->mailer->method('message')->willReturn(true);

        $response = $this->action->passwordRecovery($this->request);

        self::assertNotInstanceOf(RedirectResponse::class, $response);
        self::assertInstanceOf(Response::class, $response);
    }
}
