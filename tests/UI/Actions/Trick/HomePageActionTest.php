<?php

namespace App\Tests\UI\Actions\Trick;

use App\Application\Helpers\Interfaces\PaginationHelperInterface;
use App\Domain\Repository\TrickRepository;
use App\UI\Actions\Trick\HomePageAction;
use App\UI\Presenters\Trick\HomePagePresenter;
use App\UI\Responders\Trick\HomePageResponder;
use Doctrine\ORM\NonUniqueResultException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class HomePageActionTest extends TestCase
{
    /**
     * @var HomePageAction
     */
    private $action;

    /**
     * @var PaginationHelperInterface|MockObject
     */
    private $paginationHelper;

    public function setUp()
    {
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/url');
        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturn('vue de la page');
        $presenter = new HomePagePresenter($twig);
        $responder = new HomePageResponder($urlGenerator, $presenter);

        $repository = $this->createMock(TrickRepository::class);
        $repository->method('loadTricksWithPaging')->willReturn([]);

        $this->paginationHelper = $this->createMock(PaginationHelperInterface::class);

        $this->action = new HomePageAction(
            $repository,
            $responder,
            $this->paginationHelper
        );
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testTheRedirectResponseIfPageNumberIsFalse()
    {
        $this->paginationHelper->method('pagination')->willReturn(null);

        self::assertInstanceOf(RedirectResponse::class, $this->action->homePage());
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testResponseIfPageNumberIsTrue()
    {
        $this->paginationHelper->method('pagination')->willReturn(1);

        $response = $this->action->homePage();

        self::assertInstanceOf(Response::class, $response);
        self::assertNotInstanceOf(RedirectResponse::class, $response);
    }
}
