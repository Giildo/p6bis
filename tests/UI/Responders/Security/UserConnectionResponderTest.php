<?php

namespace App\Tests\UI\Responders\Security;

use App\UI\Presenters\Interfaces\Security\UserConnectionPresenterInterface;
use App\UI\Responders\Interfaces\Security\UserConnectionResponderInterface;
use App\UI\Responders\Security\UserConnectionResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UserConnectionResponderTest extends TestCase
{
    private $responder;

    protected function setUp()
    {
        $presenter = $this->createMock(UserConnectionPresenterInterface::class);
        $presenter->method('userConnectionPresentation')
            ->willReturn('Contenu de la vue');

        $this->responder = new UserConnectionResponder($presenter);
    }

    public function testConstructor()
    {
        self::assertInstanceOf(UserConnectionResponderInterface::class, $this->responder);
    }

    public function testRedirectionIfAttributeIsTrue()
    {
        $response = $this->responder->userConnectionResponse(true, null, null, '');

        self::assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testNotRedirectionIfAttributeIsFalse()
    {
        $form = $this->createMock(FormInterface::class);

        $error = $this->createMock(AuthenticationException::class);
        $lastUserLoaded = 'JohnDoe';

        $response = $this->responder->userConnectionResponse(
            false,
            $form,
            $error,
            $lastUserLoaded
        );

        self::assertInstanceOf(Response::class, $response);
        self::assertNotInstanceOf(RedirectResponse::class, $response);
    }
}
