<?php

namespace App\UI\Actions\Trick;

use App\UI\Responders\Interfaces\Trick\TrickDeletionResponderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

class TrickDeletionAction
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
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
     * @param EntityManagerInterface $entityManager
     * @param TrickDeletionResponderInterface $responder
     * @param FlashBagInterface $flashBag
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        TrickDeletionResponderInterface $responder,
        FlashBagInterface $flashBag
    ) {
        $this->entityManager = $entityManager;
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
     */
    public function delete(Request $request): RedirectResponse
    {
        $trick = $request->getSession()->get('trick');

        $this->entityManager->remove($trick);
        $this->entityManager->flush();

        $this->flashBag->set('messageFlash', 'La figure a été supprimée avec succès.');

        return $this->responder->response();
    }
}
