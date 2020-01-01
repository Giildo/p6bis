<?php

namespace App\Application\Handlers\Interfaces\Forms\Trick;

use App\Domain\Model\Interfaces\TrickInterface;
use Symfony\Component\Form\FormInterface;

interface NewTrickHandlerInterface
{

    /**
     * @param FormInterface $form
     * @return TrickInterface|null
     */
    public function handle(FormInterface $form): ?TrickInterface;
}
