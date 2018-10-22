<?php

namespace App\Application\Handlers\Interfaces\Forms\Comment;

use App\Domain\Model\Interfaces\TrickInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

interface AddCommentHandlerInterface
{
    /**
     * The listener tests if the comment is already in the database:
     * - if it's in the database, we update the comment
     * - if not, we create the comment
     *
     * @param FormInterface $form
     * @param TrickInterface $trick
     * @param Request $request
     *
     * @return bool
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function handle(
        FormInterface $form,
        TrickInterface $trick,
        Request $request
    ): bool;
}