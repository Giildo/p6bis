<?php

namespace App\UI\Actions\Trick;

use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Repository\TrickRepository;
use App\UI\Responders\Interfaces\Trick\TrickDeletionResponderInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

class TrickDeletionAction
{
    /**
     * @var TrickRepository
     */
    private $trickRepository;
    /**
     * @var TrickDeletionResponderInterface
     */
    private $responder;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * TrickDeletionAction constructor.
     * @param TrickRepository $trickRepository
     * @param TrickDeletionResponderInterface $responder
     * @param FlashBagInterface $flashBag
     */
    public function __construct(
        TrickRepository $trickRepository,
        TrickDeletionResponderInterface $responder,
        FlashBagInterface $flashBag
    ) {
        $this->trickRepository = $trickRepository;
        $this->responder = $responder;
        $this->flashBag = $flashBag;
    }

    /**
     * @Route(
     *     path="/espace-utilisateur/trick/suppression/{trickSlug}",
     *     requirements={"trickSlug"="\w+"},
     *     name="Trick_deletion"
     * )
     *
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Request $request): RedirectResponse
    {
        $this->trickRepository->deleteTrick(
            $request->getSession()->get('trick')
        );

        $request->getSession()->clear();

        $this->flashBag->set('messageFlash', 'La figure a été supprimée avec succès.');

        return $this->responder->response();
    }
}
