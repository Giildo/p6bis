<?php

namespace App\Tests\UI\Actions\GCU;

use App\UI\Actions\GCU\GCUAction;
use App\UI\Presenters\GCU\GCUPresenter;
use App\UI\Responders\GCU\GCUResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class GCUActionTest extends TestCase
{
    public function testTheResponseOfAction()
    {
        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturn('vue de la page');
        $presenter = new GCUPresenter($twig);
        $responder = new GCUResponder($presenter);

        $action = new GCUAction($responder);

        $response = $action->gcuPresentation();

        self::assertInstanceOf(Response::class, $response);
        self::assertNotInstanceOf(RedirectResponse::class, $response);
    }
}
