<?php

namespace App\UI\Actions\Trick;

use App\Application\Helpers\Interfaces\PaginationHelperInterface;
use App\Domain\Model\Trick;
use App\Domain\Repository\TrickRepository;
use App\UI\Responders\Interfaces\Trick\HomePageResponderInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageAction
{
    /**
     * @var TrickRepository
     */
    private $trickRepository;
    /**
     * @var HomePageResponderInterface
     */
    private $responder;
    /**
     * @var PaginationHelperInterface
     */
    private $paginationHelper;

    /**
     * HomePageAction constructor.
     * @param TrickRepository $trickRepository
     * @param HomePageResponderInterface $responder
     * @param PaginationHelperInterface $paginationHelper
     */
    public function __construct(
        TrickRepository $trickRepository,
        HomePageResponderInterface $responder,
        PaginationHelperInterface $paginationHelper
    ) {
        $this->trickRepository = $trickRepository;
        $this->responder = $responder;
        $this->paginationHelper = $paginationHelper;
    }

    /**
     * @Route(path="/accueil",name="Home")
     *
     * @return Response
     *
     * @throws NonUniqueResultException
     */
    public function homePage(): Response
    {
        $paging = 1;

        $numberPage = $this->paginationHelper->pagination(
            $this->trickRepository,
            Trick::NUMBER_OF_ITEMS,
            $paging
        );

        if (is_null($numberPage)) {
            return $this->responder->homePageResponse([], true, 1);
        }

        $tricks = $this->trickRepository->loadTricksWithPaging($paging);

        return $this->responder->homePageResponse($tricks, false, $numberPage, $paging);
    }
}
