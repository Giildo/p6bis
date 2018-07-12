<?php

namespace App\Tests\UI\Responders\Security;

use App\UI\Presenters\Interfaces\Security\UserRegistrationPresenterInterface;
use App\UI\Responders\Interfaces\Security\UserRegistrationResponderInterface;
use App\UI\Responders\Security\UserRegistrationResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class UserRegistrationResponderTest extends TestCase
{
    private $responder;

    protected function setUp()
    {
        $presenter = $this->createMock(UserRegistrationPresenterInterface::class);
        $presenter->method('userRegistrationPresentation')
                  ->willReturn('Contenu de la vue');

        $this->responder = new UserRegistrationResponder($presenter);
    }

    public function testConstructor()
    {
        self::assertInstanceOf(UserRegistrationResponderInterface::class, $this->responder);
    }

    public function testRedirectionIfAttributeIsTrue()
    {
        $response = $this->responder->userRegistrationResponse(true, null);

        self::assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testNotRedirectionIfAttributeIsFalse()
    {
        $form = $this->createMock(FormInterface::class);

        $response = $this->responder->userRegistrationResponse(false, $form);

        self::assertInstanceOf(Response::class, $response);
        self::assertNotInstanceOf(RedirectResponse::class, $response);
    }
}
