<?php

namespace App\UI\Actions\Trick;

use App\Domain\Model\Trick;
use App\UI\Responders\Interfaces\Trick\ShowTrickResponderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowTrickAction
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ShowTrickResponderInterface
     */
    private $responder;

    /**
     * ShowTrickAction constructor.
     * @param EntityManagerInterface $entityManager
     * @param ShowTrickResponderInterface $responder
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ShowTrickResponderInterface $responder
    ) {
        $this->entityManager = $entityManager;
        $this->responder = $responder;
    }

    /**
     * @Route(path="/trick/{trickSlug}", name="trick_show", requirements={"trickSlug"="\w+"})
     *
     * @param string $trickSlug
     *
     * @return RedirectResponse|Response
     *
     * @throws NonUniqueResultException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function showTrick(string $trickSlug)
    {
        $trick = $this->entityManager
                      ->getRepository(Trick::class)
                      ->loadOneTrickWithCategoryAndAuthor($trickSlug);

        if (is_null($trick)) {
            return $this->responder->showTrickResponse();
        }

        return $this->responder->showTrickResponse(
            false,
            $trick
        );
    }
}
