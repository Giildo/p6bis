<?php

namespace App\Application\Helpers\Interfaces;

interface SluggerHelperInterface
{
    /**
     * @param string $text
     *
     * @return string
     */
    public function slugify(string $text): string;
}