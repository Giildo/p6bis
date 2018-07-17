<?php

namespace App\Tests\UI\Responders\Security;

use App\UI\Presenters\Interfaces\Security\PasswordRecoveryPresenterInterface;
use App\UI\Responders\Interfaces\Security\PasswordRecoveryResponderInterface;
use App\UI\Responders\Security\PasswordRecoveryResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class PasswordRecoveryResponderTest extends TestCase
{
    private $responder;

    public function setUp()
    {
        $presenter = $this->createMock(PasswordRecoveryPresenterInterface::class);
        $presenter->method('passwordRecoveryPresentation')->willReturn('Vue de retour');

        $this->responder = new PasswordRecoveryResponder($presenter);
    }

    public function testConstructor()
    {
        self::assertInstanceOf(PasswordRecoveryResponderInterface::class, $this->responder);
    }

    public function testRedirectionIfAttributeIsTrue()
    {
        $response = $this->responder->passwordRecoveryResponse();

        self::assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testNotRedirectionIfAttributeIsFalse()
    {
        $response = $this->responder->passwordRecoveryResponse(false);

        self::assertInstanceOf(Response::class, $response);
        self::assertNotInstanceOf(RedirectResponse::class, $response);
    }

    public function testNotRedirectionIfAttributeIsFalseAndForm()
    {
        $form = $this->createMock(FormInterface::class);
        $response = $this->responder->passwordRecoveryResponse(false, $form, 'forPassword');

        self::assertInstanceOf(Response::class, $response);
        self::assertNotInstanceOf(RedirectResponse::class, $response);
    }
}
