<?php

namespace App\Application\Handlers\Forms\Security;

use App\Application\Handlers\Interfaces\Forms\Security\PasswordRecoveryForPasswordHandlerInterface;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class PasswordRecoveryForPasswordHandler implements PasswordRecoveryForPasswordHandlerInterface
{
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * PasswordRecoveryForPasswordHandler constructor.
     * @param UserRepository $repository
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(UserRepository $repository, EncoderFactoryInterface $encoderFactory)
    {
        $this->repository = $repository;
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @param FormInterface $form
     * @param Request $request
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     */
    public function handle(FormInterface $form, Request $request): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->repository->loadUserByToken(
                $request->query->get('ut')
            );

            if (is_null($user)) {
                return false;
            }

            $dto = $form->getData();

            $encoder = $this->encoderFactory->getEncoder(User::class);
            $user->changePassword($encoder->encodePassword($dto->password, ''));
            $user->deleteToken();

            $this->repository->saveUser($user);

            return true;
        }

        return false;
    }
}
