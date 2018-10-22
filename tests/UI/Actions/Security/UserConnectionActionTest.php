<?php

namespace App\Tests\UI\Actions\Security;

use App\UI\Actions\Security\UserConnectionAction;
use App\UI\Presenters\Security\UserConnectionPresenter;
use App\UI\Responders\Security\UserConnectionResponder;
use Symfony\Bundle\WebProfilerBundle\Tests\TestCase;
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

class UserConnectionActionTest extends TestCase
{
    /**
     * @var UserConnectionAction
     */
    private $userRegistrationAction;

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

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/url');

        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturn('vue de la page');
        $presenter = new UserConnectionPresenter($twig);
        $responder = new UserConnectionResponder($presenter, $urlGenerator);

        $this->request = new Request();

        $this->userRegistrationAction = new UserConnectionAction(
            $responder,
            $formFactory
        );
    }

    /**
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function testNoRedirectionIfUserIsntConnected()
    {
        $response = $this->userRegistrationAction->connection($this->request);

        self::assertInstanceOf(Response::class, $response);
        self::assertNotInstanceOf(RedirectResponse::class, $response);
    }

}
