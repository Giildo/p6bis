<?php

namespace App\UI\Presenters\Interfaces\Trick;

use App\Domain\Model\Interfaces\TrickInterface;

interface HomePagePresenterInterface
{
    /**
     * @param TrickInterface[]|null $tricks
     *
     * @return string
     */
    public function homePagePresentation(?array $tricks = []): string;
}
