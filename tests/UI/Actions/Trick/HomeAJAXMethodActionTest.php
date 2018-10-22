<?php

namespace App\Tests\UI\Actions\Trick;

use App\Application\Helpers\PaginationHelper;
use App\Domain\Repository\TrickRepository;
use App\Tests\Fixtures\Traits\TrickAndCategoryFixtures;
use App\UI\Actions\Trick\HomeAJAXMethodAction;
use App\UI\Presenters\Trick\HomeAJAXMethodPresenter;
use App\UI\Responders\Trick\HomeAJAXMethodResponder;
use Doctrine\ORM\NonUniqueResultException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

class HomeAJAXMethodActionTest extends TestCase
{
    /**
     * @var TrickRepository|MockObject
     */
    private $trickRepository;

    /**
     * @var HomeAJAXMethodAction
     */
    private $action;

    public function setUp()
    {
        $this->constructCategoryAndTrick();

        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturn('Vue de la page');

        $presenter = new HomeAJAXMethodPresenter($twig);

        $responder = new HomeAJAXMethodResponder($presenter);

        $paginationHelper = new PaginationHelper();

        $this->trickRepository = $this->createMock(TrickRepository::class);
        $this->trickRepository->method('loadTricksWithPaging')->willReturn
        ([$this->mute]);

        $this->action = new HomeAJAXMethodAction($responder, $paginationHelper,
                                           $this->trickRepository);
    }

    use TrickAndCategoryFixtures;

    /**
     * @throws NonUniqueResultException
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function testReturnResponse200IfThePagingIsCorrect()
    {
        $this->trickRepository->method('countEntries')->willReturn(22);

        $return = $this->action->ajaxMethod(3);
        self::assertInstanceOf(Response::class, $return);
        self::assertNotInstanceOf(RedirectResponse::class, $return);
        self::assertEquals(Response::HTTP_OK, $return->getStatusCode());
    }

    /**
     * @throws NonUniqueResultException
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function testReturnResponse404IfThePagingIsIncorrect()
    {
        $this->trickRepository->method('countEntries')->willReturn(12);

        $return = $this->action->ajaxMethod(3);
        self::assertInstanceOf(Response::class, $return);
        self::assertNotInstanceOf(RedirectResponse::class, $return);
        self::assertEquals(Response::HTTP_NOT_FOUND, $return->getStatusCode());
    }
}
