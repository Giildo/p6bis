<?php

namespace App\Application\Handlers\Interfaces\Forms\Comment;

use App\Domain\Model\Interfaces\TrickInterface;
use Symfony\Component\Form\FormInterface;

interface AddCommentHandlerInterface
{
    /**
     * @param FormInterface $form
     * @param TrickInterface $trick
     *
     * @return bool
     */
    public function handle(FormInterface $form, TrickInterface $trick): bool;
}