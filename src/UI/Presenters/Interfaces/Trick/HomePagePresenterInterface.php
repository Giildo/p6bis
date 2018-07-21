<?php

namespace App\UI\Presenters\Interfaces\Trick;

interface HomePagePresenterInterface
{
    /**
     * @param array|null $tricks
     * @return string
     */
    public function homePagePresentation(?array $tricks = []): string;
}
