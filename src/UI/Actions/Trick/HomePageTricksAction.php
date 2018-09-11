<?php

namespace App\UI\Actions\Trick;

use App\Domain\Repository\TrickRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class HomePageTricksAction
{
    /**
     * @var TrickRepository
     */
    private $trickRepository;
    /**
     * @var Environment
     */
    private $twig;


    /**
     * HomePageTricksAction constructor.
     * @param TrickRepository $trickRepository
     * @param Environment $twig
     */
    public function __construct(
        TrickRepository $trickRepository,
        Environment $twig
    )
    {
        $this->trickRepository = $trickRepository;
        $this->twig = $twig;
    }

    /**
     * @Route(path="/tricks/{paging}", name="Tricks_load", requirements={"paging": "\d+"})
     * @param int $paging
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function trickReturn(int $paging, Request $request): Response
    {
        $tricks = $this->trickRepository->loadTricksWithPaging($paging);
        return new Response($this->twig->render('Trick/home_load.html.twig', compact('tricks')));
    }
}
