<?php

namespace App\UI\Actions\Trick;

use App\Application\Helpers\Interfaces\PaginationHelperInterface;
use App\Domain\Model\Trick;
use App\Domain\Repository\TrickRepository;
use App\UI\Responders\Interfaces\Trick\HomePageResponderInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

class HomePageAction
{
    /**
     * @var TrickRepository
     */
    private $trickRepository;
    /**
     * @var HomePageResponderInterface
     */
    private $homePageResponder;
    /**
     * @var PaginationHelperInterface
     */
    private $paginationHelper;

    /**
     * HomePageAction constructor.
     * @param TrickRepository $trickRepository
     * @param HomePageResponderInterface $homePageResponder
     * @param PaginationHelperInterface $paginationHelper
     */
    public function __construct(
        TrickRepository $trickRepository,
        HomePageResponderInterface $homePageResponder,
        PaginationHelperInterface $paginationHelper
    ) {
        $this->trickRepository = $trickRepository;
        $this->homePageResponder = $homePageResponder;
        $this->paginationHelper = $paginationHelper;
    }

    /**
     * Returns the list of tricks.
     * @uses PaginationHelperInterface for pagination. The following tricks
     * are called by AJAX method.
     *
     * @Route(
     *     path="/accueil",
     *     name="Home"
     * )
     *
     * @return Response
     *
     * @throws NonUniqueResultException
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function homePage(): Response
    {
        $paging = 1;

        $numberPage = $this->paginationHelper->pagination(
            $this->trickRepository,
            Trick::NUMBER_OF_ITEMS,
            $paging
        );

        $tricks = $this->trickRepository->loadTricksWithPaging($paging);

        return $this->homePageResponder->homePageResponse(
            $tricks, $numberPage, $paging
        );
    }
}
