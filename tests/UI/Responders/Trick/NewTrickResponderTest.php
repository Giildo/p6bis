<?php

namespace App\Tests\UI\Responders\Trick;

use App\Domain\Model\Interfaces\TrickInterface;
use App\UI\Presenters\Interfaces\Trick\NewTrickPresenterInterface;
use App\UI\Responders\Interfaces\Trick\NewTrickResponderInterface;
use App\UI\Responders\Trick\NewTrickResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class NewTrickResponderTest extends TestCase
{
    private $responder;

    protected function setUp()
    {
        $presenter = $this->createMock(NewTrickPresenterInterface::class);
        $presenter->method('newTrickPresentation')->willReturn('Vue de la page');

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('url');

        $this->responder = new NewTrickResponder($presenter, $urlGenerator);
    }

    public function testConstructor()
    {
        self::assertInstanceOf(NewTrickResponderInterface::class, $this->responder);
    }

    public function testTheRedirectResponseIfRedictParamIsTrue()
    {
        $trick = $this->createMock(TrickInterface::class);
        $trick->method('getSlug')->willReturn('slug');

        $response = $this->responder->response(true, $trick);

        self::assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testTheResponseIfRedirectParamIsFalse()
    {
        $form = $this->createMock(FormInterface::class);

        $response = $this->responder->response(false, null, $form);

        self::assertInstanceOf(Response::class, $response);
        self::assertNotInstanceOf(RedirectResponse::class, $response);
    }
}
