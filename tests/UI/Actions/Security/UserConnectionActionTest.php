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
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserConnectionActionTest extends TestCase
{
    private $userRegistrationAction;

    private $authorizationChecker;

    private $authenticationUtils;

    private $request;

    protected function setUp()
    {
        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);

        $this->authenticationUtils = $this->createMock(AuthenticationUtils::class);
        $this->authenticationUtils->method('getLastAuthenticationError')
                                  ->willReturn(null);
        $this->authenticationUtils->method('getLastUsername')
                                  ->willReturn('');

        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formInterface = $this->createMock(FormInterface::class);
        $formFactory->method('create')->willReturn($formInterface);
        $formInterface->method('handleRequest')->willReturnSelf();

        $presenter = $this->createMock(UserConnectionPresenterInterface::class);
        $responder = new UserConnectionResponder($presenter);

        $this->request = $this->createMock(Request::class);

        $this->userRegistrationAction = new UserConnectionAction(
            $this->authorizationChecker,
            $responder,
            $formFactory
        );
    }

    public function testConstructor()
    {
        self::assertInstanceOf(UserConnectionAction::class, $this->userRegistrationAction);
    }

    public function testRedirectionIfUserIsConnected()
    {
        $this->authorizationChecker->method('isGranted')->willReturn(true);

        $response = $this->userRegistrationAction->connection($this->request, $this->authenticationUtils);

        self::assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testNoRedirectionIfUserIsntConnected()
    {
        $this->authorizationChecker->method('isGranted')->willReturn(false);

        $response = $this->userRegistrationAction->connection($this->request, $this->authenticationUtils);

        self::assertInstanceOf(Response::class, $response);
        self::assertNotInstanceOf(RedirectResponse::class, $response);
    }

}
