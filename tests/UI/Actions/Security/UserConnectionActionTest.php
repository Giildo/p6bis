<?php

namespace App\Tests\UI\Actions\Security;

use App\UI\Actions\Security\UserConnectionAction;
use App\UI\Presenters\Interfaces\Security\UserConnectionPresenterInterface;
use App\UI\Responders\Security\UserConnectionResponder;
use Symfony\Bundle\WebProfilerBundle\Tests\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserConnectionActionTest extends TestCase
{
    private $userRegistrationAction;

    private $authenticationUtils;

    private $request;

    protected function setUp()
    {
        $this->authenticationUtils = $this->createMock(AuthenticationUtils::class);
        $this->authenticationUtils->method('getLastAuthenticationError')
                                  ->willReturn(null);
        $this->authenticationUtils->method('getLastUsername')
                                  ->willReturn('');

        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formInterface = $this->createMock(FormInterface::class);
        $formFactory->method('create')->willReturn($formInterface);
        $formInterface->method('handleRequest')->willReturnSelf();

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('url');

        $presenter = $this->createMock(UserConnectionPresenterInterface::class);
        $responder = new UserConnectionResponder($presenter, $urlGenerator);

        $this->request = $this->createMock(Request::class);

        $this->userRegistrationAction = new UserConnectionAction(
            $responder,
            $formFactory
        );
    }

    public function testConstructor()
    {
        self::assertInstanceOf(UserConnectionAction::class, $this->userRegistrationAction);
    }

    public function testNoRedirectionIfUserIsntConnected()
    {
        $response = $this->userRegistrationAction->connection($this->request, $this->authenticationUtils);

        self::assertInstanceOf(Response::class, $response);
        self::assertNotInstanceOf(RedirectResponse::class, $response);
    }

}
