<?php

namespace App\Application\Handlers\Interfaces\Forms\Trick;

use App\Domain\Model\Interfaces\TrickInterface;
use Symfony\Component\Form\FormInterface;

interface TrickModificationHandlerInterface
{
    /**
     * @param FormInterface $form
     * @param TrickInterface $trick
     *
     * @return bool
     */
    public function handle(FormInterface $form, TrickInterface $trick): bool;
}