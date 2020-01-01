<?php

namespace App\Tests\UI\Actions\Security;

use App\Application\Handlers\Interfaces\Forms\Security\PasswordRecoveryForPasswordHandlerInterface;
use App\UI\Actions\Security\PasswordRecoveryPasswordAction;
use App\UI\Presenters\Security\PasswordRecoveryPresenter;
use App\UI\Responders\Security\PasswordRecoveryResponder;
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

class PasswordRecoveryPasswordActionTest extends TestCase
{
    /**
     * @var PasswordRecoveryPasswordAction
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

    public function setUp()
    {
        $form = $this->createMock(FormInterface::class);
        $form->method('handleRequest')->willReturnSelf();
        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formFactory->method('create')->willReturn($form);

        $this->forPasswordHandler = $this->createMock(PasswordRecoveryForPasswordHandlerInterface::class);

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/url');

        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturn('vue de la page');
        $presenter = new PasswordRecoveryPresenter($twig);
        $responder = new PasswordRecoveryResponder($presenter, $urlGenerator);

        $this->request = new Request();
        $this->flashBag = new FlashBag();

        $this->action = new PasswordRecoveryPasswordAction(
            $formFactory,
            $this->forPasswordHandler,
            $responder
        );
    }

    /**
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function testRedirectResponseIfHandlerReturnTrue()
    {
        $this->forPasswordHandler->method('handle')->willReturn(true);

        self::assertInstanceOf(
            RedirectResponse::class,
            $this->action->passwordRecovery($this->request, $this->flashBag)
        );
    }

    /**
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function testRedirectResponseIfHandlerReturnFalse()
    {
        $this->forPasswordHandler->method('handle')->willReturn(false);

        $response = $this->action->passwordRecovery($this->request, $this->flashBag);

        self::assertNotInstanceOf(RedirectResponse::class, $response);
        self::assertInstanceOf(Response::class, $response);
    }
}
