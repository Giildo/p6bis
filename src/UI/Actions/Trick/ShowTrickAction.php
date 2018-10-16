<?php

namespace App\UI\Actions\Trick;

use App\Application\FormFactory\Interfaces\CommentModificationFormFactoryInterface;
use App\Application\Handlers\Interfaces\Forms\Comment\AddCommentHandlerInterface;
use App\Application\Helpers\Interfaces\PaginationHelperInterface;
use App\Domain\Model\Comment;
use App\Domain\Repository\CommentRepository;
use App\Domain\Repository\TrickRepository;
use App\UI\Responders\Interfaces\Trick\ShowTrickResponderInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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
     * @var CommentRepository
     */
    private $commentRepository;
    /**
     * @var PaginationHelperInterface
     */
    private $paginationHelper;

    /**
     * ShowTrickAction constructor.
     * @param TrickRepository $trickRepository
     * @param ShowTrickResponderInterface $responder
     * @param CommentModificationFormFactoryInterface $formFactory
     * @param AddCommentHandlerInterface $handler
     * @param CommentRepository $commentRepository
     * @param PaginationHelperInterface $paginationHelper
     */
    public function __construct(
        TrickRepository $trickRepository,
        ShowTrickResponderInterface $responder,
        CommentModificationFormFactoryInterface $formFactory,
        AddCommentHandlerInterface $handler,
        CommentRepository $commentRepository,
        PaginationHelperInterface $paginationHelper
    ) {
        $this->trickRepository = $trickRepository;
        $this->responder = $responder;
        $this->formFactory = $formFactory;
        $this->handler = $handler;
        $this->commentRepository = $commentRepository;
        $this->paginationHelper = $paginationHelper;
    }

    /**
     * @Route(
     *     path="/trick/{trickSlug}/ComPage/{paging}",
     *     name="Trick_show",
     *     requirements={
     *          "trickSlug"="[a-zA-Z0-9-]+",
     *          "paging"="\d+"
     *     },
     *     defaults={"paging"=1}
     * )
     *
     * @param Request $request
     * @param string $trickSlug
     * @param int $paging
     *
     * @return RedirectResponse|Response
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function showTrick(Request $request, string $trickSlug, int $paging)
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
                ['trickSlug' => $trickSlug, '_fragment' => false]
            );
        }

        $numberPage = $this->paginationHelper->pagination(
            $this->commentRepository,
            Comment::NUMBER_OF_ITEMS,
            $paging,
            $trickSlug
        );

        if (is_null($numberPage)) {
            return $this->responder->showTrickResponse(
                true,
                'Trick_show',
                ['trickSlug' => $trickSlug]
            );
        }

        $comments = $this->commentRepository->loadCommentsWithPagination($trickSlug, $paging);

        return $this->responder->showTrickResponse(
            false,
            '',
            [],
            $trick,
            $comments,
            $formComment,
            $numberPage,
            $paging
        );
    }
}
