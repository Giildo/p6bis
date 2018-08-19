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
     * @Route(path="/accueil", name="Home")
     *
     * @return Response
     */
    public function homePage(): Response
    {
        $tricks = $this->trickRepository->loadAllTricksWithAuthorCategoryAndHeadPicture();

        return $this->responder->homePageResponse($tricks);
    }
}
