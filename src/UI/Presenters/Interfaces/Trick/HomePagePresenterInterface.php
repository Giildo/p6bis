<?php

namespace App\UI\Presenters\Interfaces\Trick;

use App\Domain\Model\Interfaces\TrickInterface;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

interface HomePagePresenterInterface
{
    /**
     * @param TrickInterface[]|null $tricks
     * @param int $currentPage
     *
     * @param int $pageNumber
     * @return string
     *
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function homePagePresentation(
        array $tricks,
        int $currentPage,
        ?int $pageNumber = 0
    ): string;
}
