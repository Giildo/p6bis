<?php

namespace App\UI\Responders\Interfaces\User;

use App\Domain\Model\Interfaces\UserInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

interface ProfilePictureResponderInterface
{
    /**
     * @param FormInterface $form
     * @param UserInterface $user
     *
     * @return Response
     */
    public function response(FormInterface $form, UserInterface $user): Response;
}
