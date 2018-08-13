<?php

namespace App\UI\Presenters\Interfaces\Trick;

use Symfony\Component\Form\FormInterface;

interface NewTrickPresenterInterface
{
    /**
     * @param FormInterface $form
     *
     * @return string
     */
    public function newTrickPresentation(FormInterface $form): string;
}
