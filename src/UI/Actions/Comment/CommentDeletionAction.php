<?php

namespace App\UI\Actions\Comment;

use App\Domain\Repository\CommentRepository;
use App\UI\Responders\Interfaces\Comment\CommentDeletionResponderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class CommentDeletionAction
{
    /**
     * @var CommentRepository
     */
    private $commentRepository;
    /**
     * @var CommentDeletionResponderInterface
     */
    private $responder;

    /**
     * CommentDeletionAction constructor.
     * @param CommentRepository $commentRepository
     * @param CommentDeletionResponderInterface $responder
     */
    public function __construct(
        CommentRepository $commentRepository,
        CommentDeletionResponderInterface $responder
    ) {
        $this->commentRepository = $commentRepository;
        $this->responder = $responder;
    }

    /**
     * @Route(
     *     path="/trick/{trickSlug}/suppression-commentaire/{id}",
     *     requirements={"trickSlug"="\w+", "id"="[\w\-\d]+"},
     *     name="Trick_show_comment_deletion"
     * )
     *
     * @param Request $request
     * @param string $trickSlug
     *
     * @return RedirectResponse
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function commentDeletion(Request $request, string $trickSlug): RedirectResponse
    {
        $comment = $request->getSession()->get('comment');

        if (is_null($comment)) {
            return $this->responder->response($trickSlug);
        }

        $this->commentRepository->deleteComment($comment);

        return $this->responder->response($trickSlug);
    }
}
