<?php

namespace App\Application\Handlers\Interfaces\Forms\User;

use App\Domain\Model\Interfaces\UserInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Form\FormInterface;

interface ProfilePictureHandlerInterface
{
    /**
     * @param FormInterface $form
     * @param UserInterface $user
     *
     * @return bool
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function handle(FormInterface $form, UserInterface $user): bool;
}
