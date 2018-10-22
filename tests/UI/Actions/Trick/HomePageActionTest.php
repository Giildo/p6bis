<?php

namespace App\Tests\UI\Actions\Trick;

use App\Application\Helpers\Interfaces\PaginationHelperInterface;
use App\Domain\Repository\TrickRepository;
use App\UI\Actions\Trick\HomePageAction;
use App\UI\Presenters\Trick\HomePagePresenter;
use App\UI\Responders\Trick\HomePageResponder;
use Doctrine\ORM\NonUniqueResultException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

class HomePageActionTest extends TestCase
{
    /**
     * @throws NonUniqueResultException
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function testResponseIfPageNumberIsTrue()
    {
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/url');
        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturn('vue de la page');
        $presenter = new HomePagePresenter($twig);
        $responder = new HomePageResponder($presenter);

        $repository = $this->createMock(TrickRepository::class);
        $repository->method('loadTricksWithPaging')->willReturn([]);

        $paginationHelper = $this->createMock(PaginationHelperInterface::class);
        $paginationHelper->method('pagination')->willReturn(1);

        $action = new HomePageAction(
            $repository,
            $responder,
            $paginationHelper
        );

        $response = $action->homePage();

        self::assertInstanceOf(Response::class, $response);
        self::assertNotInstanceOf(RedirectResponse::class, $response);
    }
}
