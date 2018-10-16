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
     * @param int $pageNumber
     * @param int $currentPage
     *
     * @return string
     *
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function homePagePresentation(
        array $tricks,
        int $pageNumber,
        int $currentPage
    ): string;
}
