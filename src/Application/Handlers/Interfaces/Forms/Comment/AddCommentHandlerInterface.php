<?php

namespace App\Application\Handlers\Interfaces\Forms\Comment;

use App\Domain\Model\Interfaces\TrickInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

interface AddCommentHandlerInterface
{
    /**
     * @param FormInterface $form
     * @param TrickInterface $trick
     * @param Request $request
     *
     * @return bool
     */
    public function handle(
        FormInterface $form,
        TrickInterface $trick,
        Request $request
    ): bool;
}