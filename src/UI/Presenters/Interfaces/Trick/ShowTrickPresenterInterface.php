<?php

namespace App\UI\Presenters\Interfaces\Trick;

use App\Domain\Model\Trick;

interface ShowTrickPresenterInterface
{
    /**
     * @param Trick $trick
     *
     * @return string
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function showTrickPresentation(Trick $trick): string;
}