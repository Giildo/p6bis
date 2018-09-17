<?php

namespace App\Application\Handlers\Interfaces\Forms\User;

use App\Domain\Model\Interfaces\UserInterface;
use Symfony\Component\Form\FormInterface;

interface ProfilePictureHandlerInterface
{
    /**
     * @param FormInterface $form
     * @param UserInterface $user
     *
     * @return bool
     */
    public function handle(FormInterface $form, UserInterface $user): bool;
}
