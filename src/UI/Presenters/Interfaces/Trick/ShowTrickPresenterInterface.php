<?php

namespace App\UI\Presenters\Interfaces\Trick;

use App\Domain\Model\Interfaces\TrickInterface;

interface ShowTrickPresenterInterface
{
    /**
     * @param TrickInterface $trick
     *
     * @return string
     */
    public function showTrickPresentation(TrickInterface $trick): string;
}