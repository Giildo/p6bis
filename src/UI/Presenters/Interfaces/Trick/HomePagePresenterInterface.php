<?php

namespace App\UI\Presenters\Interfaces\Trick;

interface HomePagePresenterInterface
{
    /**
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function homePagePresentation(): string;
}
