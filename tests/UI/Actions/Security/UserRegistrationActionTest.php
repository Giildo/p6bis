<?php

namespace App\Tests\UI\Actions\Security;

use App\Application\Handlers\Interfaces\Forms\Security\UserRegistrationHandlerInterface;
use App\UI\Actions\Security\UserRegistrationAction;
use App\UI\Presenters\Security\UserRegistrationPresenter;
use App\UI\Responders\Security\UserRegistrationResponder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class UserRegistrationActionTest extends TestCase
{
    /**
     * @var UserRegistrationAction
     */
    private $userRegistrationAction;

    /**
     * @var UserRegistrationHandlerInterface|MockObject
     */
    private $handler;

    /**
     * @var Request
     */
    private $request;

    protected function setUp()
    {
        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formInterface = $this->createMock(FormInterface::class);
        $formFactory->method('create')->willReturn($formInterface);
        $formInterface->method('handleRequest')->willReturnSelf();

        $this->handler = $this->createMock(UserRegistrationHandlerInterface::class);

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/url');

        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturn('vue de la page');
        $presenter = new UserRegistrationPresenter($twig);
        $responder = new UserRegistrationResponder($presenter, $urlGenerator);

        $this->request = new Request();

        $this->userRegistrationAction = new UserRegistrationAction(
            $formFactory,
            $this->handler,
            $responder
        );
    }

    public function testRedirectionIfHandlerIsTrue()
    {
        $this->handler->method('handle')->willReturn(true);

        self::assertInstanceOf(
            RedirectResponse::class,
            $this->userRegistrationAction->registration($this->request)
        );
    }

    public function testNotRedirectionIfHandlerIsFalse()
    {
        $this->handler->method('handle')->willReturn(false);

        $response = $this->userRegistrationAction->registration($this->request);

        self::assertInstanceOf(Response::class, $response);
        self::assertNotInstanceOf(RedirectResponse::class, $response);
    }
}
