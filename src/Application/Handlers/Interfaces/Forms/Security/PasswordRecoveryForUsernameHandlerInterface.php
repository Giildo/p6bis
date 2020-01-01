<?php

namespace App\Application\Handlers\Interfaces\Forms\Security;

use App\Domain\Model\Interfaces\UserInterface;
use Doctrine\ORM\ORMException;
use Exception;
use Symfony\Component\Form\FormInterface;

interface PasswordRecoveryForUsernameHandlerInterface
{
    /**
     * @param FormInterface $form
     *
     * @return UserInterface|null
     *
     * @throws ORMException
     * @throws Exception
     */
    public function handle(FormInterface $form): ?UserInterface;
}