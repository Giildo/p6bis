<?php

namespace App\UI\Actions\Trick;

use App\Domain\Repository\TrickRepository;
use App\UI\Responders\Interfaces\Trick\HomePageResponderInterface;
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
     * HomePageAction constructor.
     * @param TrickRepository $trickRepository
     * @param HomePageResponderInterface $responder
     */
    public function __construct(
        TrickRepository $trickRepository,
        HomePageResponderInterface $responder
    ) {
        $this->trickRepository = $trickRepository;
        $this->responder = $responder;
    }

    /**
     * @Route(
     *     path="/accueil/{paging}",
     *     requirements={"paging": "\d+"},
     *     defaults={"paging": "1"},
     *     name="Home"
     * )
     *
     * @param int $paging
     *
     * @return Response
     */
    public function homePage(int $paging): Response
    {
        $numberPage = (int)ceil($this->trickRepository->countTricks() / 10);

        if ($paging < 1) {
            return $this->responder->homePageResponse([], true, 1);
        } elseif ($paging > $numberPage) {
            return $this->responder->homePageResponse([], true, $numberPage);
        }

        $tricks = $this->trickRepository->loadTricksWithPaging($paging);

        return $this->responder->homePageResponse($tricks, false, null, $numberPage, $paging);
    }
}
