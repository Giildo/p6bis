<?php

namespace App\Application\Handlers\Interfaces\Forms\Trick;

use App\Domain\Model\Trick;
use Symfony\Component\Form\FormInterface;

interface NewTrickHandlerInterface
{

    /**
     * @param FormInterface $form
     * @return Trick|null
     */
    public function handle(FormInterface $form): ?Trick;
}
