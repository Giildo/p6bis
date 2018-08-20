<?php

namespace App\UI\Actions\Trick;

use App\Application\FormFactory\Interfaces\CommentModificationFormFactoryInterface;
use App\Application\Handlers\Interfaces\Forms\Comment\AddCommentHandlerInterface;
use App\Domain\Repository\TrickRepository;
use App\UI\Responders\Interfaces\Trick\ShowTrickResponderInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowTrickAction
{
    /**
     * @var TrickRepository
     */
    private $trickRepository;
    /**
     * @var ShowTrickResponderInterface
     */
    private $responder;
    /**
     * @var CommentModificationFormFactoryInterface
     */
    private $formFactory;
    /**
     * @var AddCommentHandlerInterface
     */
    private $handler;

    /**
     * ShowTrickAction constructor.
     * @param TrickRepository $trickRepository
     * @param ShowTrickResponderInterface $responder
     * @param CommentModificationFormFactoryInterface $formFactory
     * @param AddCommentHandlerInterface $handler
     */
    public function __construct(
        TrickRepository $trickRepository,
        ShowTrickResponderInterface $responder,
        CommentModificationFormFactoryInterface $formFactory,
        AddCommentHandlerInterface $handler
    ) {
        $this->trickRepository = $trickRepository;
        $this->responder = $responder;
        $this->formFactory = $formFactory;
        $this->handler = $handler;
    }

    /**
     * @Route(path="/trick/{trickSlug}", name="Trick_show", requirements={"trickSlug"="\w+"})
     *
     * @param Request $request
     * @param string $trickSlug
     *
     * @return RedirectResponse|Response
     *
     * @throws NonUniqueResultException
     */
    public function showTrick(Request $request, string $trickSlug)
    {
        $trick = $this->trickRepository
                      ->loadOneTrickWithCategoryAndAuthor($trickSlug);

        if (is_null($trick)) {
            return $this->responder->showTrickResponse();
        }

        $formComment = $this->formFactory->create($request);

        if ($this->handler->handle($formComment, $trick, $request)) {
            return $this->responder->showTrickResponse(
                true,
                'Trick_show',
                ['trickSlug' => $trickSlug]
            );
        }

        return $this->responder->showTrickResponse(
            false,
            '',
            [],
            $trick,
            $formComment
        );
    }
}
