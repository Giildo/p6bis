<?php

namespace App\UI\Presenters\Interfaces\GCU;

use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

interface GCUPresenterInterface
{
    /**
     * @return string
     *
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function presentation(): string;
}
