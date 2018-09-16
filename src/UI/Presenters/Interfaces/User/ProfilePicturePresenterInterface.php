<?php

namespace App\UI\Presenters\Interfaces\User;

use App\Domain\Model\Interfaces\UserInterface;
use Symfony\Component\Form\FormInterface;

interface ProfilePicturePresenterInterface
{
    /**
     * @param FormInterface $form
     * @param UserInterface $user
     *
     * @return string
     */
    public function presentation(
        FormInterface $form,
        UserInterface $user
    ): string;
}
