<?php

namespace App\Application\Handlers\Forms\Security;

use App\Application\Handlers\Interfaces\Forms\Security\PasswordRecoveryForUsernameHandlerInterface;
use App\Domain\Model\Interfaces\UserInterface;
use App\Domain\Repository\UserRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class PasswordRecoveryForUsernameHandler implements PasswordRecoveryForUsernameHandlerInterface
{
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;

    /**
     * PasswordRecoveryForUsernameHandler constructor.
     * @param UserRepository $repository
     * @param TokenGeneratorInterface $tokenGenerator
     */
    public function __construct(
        UserRepository $repository,
        TokenGeneratorInterface $tokenGenerator
    ) {
        $this->repository = $repository;
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(FormInterface $form): ?UserInterface
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $dto = $form->getData();

            $user = $this->repository->loadUserByUsername($dto->username);

            if (is_null($user)) {
                $form->addError(
                    new FormError('Aucun utilisateur n\'a été trouvé avec ce nom d\'utilisateur.')
                );
                return null;
            }

            $user->createToken($this->tokenGenerator);

            $this->repository->saveUser($user);

            return $user;
        }

        return null;
    }
}
