<?php

namespace App\UI\Presenters\Interfaces\Trick;

use App\Domain\Model\Interfaces\TrickInterface;
use Symfony\Component\Form\FormInterface;

interface ShowTrickPresenterInterface
{
    /**
     * @param TrickInterface $trick
     * @param FormInterface $formComment
     *
     * @return string
     */
    public function showTrickPresentation(
        TrickInterface $trick,
        FormInterface $formComment
    ): string;
}