<?php

namespace App\UI\Actions\Trick;

use App\Domain\Model\Trick;
use App\Domain\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\UI\Responders\Interfaces\Trick\HomePageResponderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageAction
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var HomePageResponderInterface
     */
    private $responder;

    /**
     * HomePageAction constructor.
     * @param EntityManagerInterface $entityManager
     * @param HomePageResponderInterface $responder
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        HomePageResponderInterface $responder
    ) {
        $this->entityManager = $entityManager;
        $this->responder = $responder;
    }

    /**
     * @Route(path="/accueil", name="Home")
     *
     * @return Response
     */
    public function homePage(): Response
    {
        /** @var TrickRepository $repository */
        $repository = $this->entityManager->getRepository(Trick::class);

        $tricks = $repository->loadAllTricksWithAuthorCategoryAndHeadPicture();

        return $this->responder->homePageResponse($tricks);
    }
}
