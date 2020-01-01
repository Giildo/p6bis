<?php

namespace App\UI\Actions\Trick;

use App\Application\Helpers\Interfaces\PaginationHelperInterface;
use App\Domain\Model\Trick;
use App\Domain\Repository\TrickRepository;
use App\UI\Responders\Interfaces\Trick\HomeAJAXMethodResponderInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

class HomeAJAXMethodAction
{
    /**
     * @var HomeAJAXMethodResponderInterface
     */
    private $responder;
    /**
     * @var PaginationHelperInterface
     */
    private $paginationHelper;
    /**
     * @var TrickRepository
     */
    private $trickRepository;

    /**
     * HomeAJAXMethodAction constructor.
     * @param HomeAJAXMethodResponderInterface $responder
     * @param PaginationHelperInterface $paginationHelper
     * @param TrickRepository $trickRepository
     */
    public function __construct(
        HomeAJAXMethodResponderInterface $responder,
        PaginationHelperInterface $paginationHelper,
        TrickRepository $trickRepository
    ) {
        $this->responder = $responder;
        $this->paginationHelper = $paginationHelper;
        $this->trickRepository = $trickRepository;
    }

    /**
     * @Route(
     *     name="Home_AJAX_Loading",
     *     path="/tricks/{paging}",
     *     requirements={"paging": "\d+"}
     * )
     *
     * @param int $paging
     *
     * @return Response|null
     *
     * @throws NonUniqueResultException
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function ajaxMethod(int $paging): ?Response
    {
        $numberPage = $this->paginationHelper->pagination(
            $this->trickRepository,
            Trick::NUMBER_OF_ITEMS,
            $paging
        );

        if (is_null($numberPage)) {
            return $this->responder->response();
        }

        $tricks = $this->trickRepository->loadTricksWithPaging($paging);

        return $this->responder->response($tricks);
    }
}
