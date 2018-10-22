<?php

namespace App\Application\Handlers\Interfaces\Forms\Security;

use App\Domain\DTO\Security\PasswordRecoveryForPasswordDTO;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

interface PasswordRecoveryForPasswordHandlerInterface
{
    /**
     * The handler:
     * - recovers the 'ut', user token, option in queries.
     * - @uses UserRepository to loads user with her token.
     * - verifies if the token date isn't exceeded.
     * - recovers the datas passed by form and
     *   @uses PasswordRecoveryForPasswordDTO for password modification
     * - saves the user
     *
     * @param FormInterface $form
     * @param Request $request
     *
     * @return bool
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function handle(
        FormInterface $form,
        Request $request
    ): bool;
}