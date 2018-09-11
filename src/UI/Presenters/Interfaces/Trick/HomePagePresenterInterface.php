<?php

namespace App\UI\Presenters\Interfaces\Trick;

use App\Domain\Model\Interfaces\TrickInterface;

interface HomePagePresenterInterface
{
    /**
     * @param TrickInterface[]|null $tricks
     * @param int $pageNumber
     * @param int $currentPage
     *
     * @return string
     */
    public function homePagePresentation(
        array $tricks,
        int $pageNumber,
        int $currentPage
    ): string;
}
