<?php

namespace App\Application\Handlers\Forms\Security;

use App\Application\Handlers\Interfaces\Forms\Security\PasswordRecoveryForPasswordHandlerInterface;
use App\Domain\Repository\UserRepository;
use DateTime;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordRecoveryForPasswordHandler
    implements PasswordRecoveryForPasswordHandlerInterface
{
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * PasswordRecoveryForPasswordHandler constructor.
     * @param UserRepository $repository
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(
        UserRepository $repository,
        UserPasswordEncoderInterface $encoder
    ) {
        $this->repository = $repository;
        $this->encoder = $encoder;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(
        FormInterface $form,
        Request $request
    ): bool {
        if ($form->isSubmitted() && $form->isValid()) {
            if (is_null($token = $request->query->get('ut'))) {
                $form->addError(
                    new FormError(
                        'Une erreur est survenue avec le lien renseigné,
                        veuillez réessayer ou refaire une demande de récupération pour votre mot de passe.'
                    )
                );
                return false;
            }

            $user = $this->repository->loadUserByToken($token);

            if (is_null($user)) {
                $form->addError(
                    new FormError(
                        'Une erreur est survenue avec le lien renseigné,
                        veuillez réessayer ou refaire une demande de récupération pour votre mot de passe.'
                    )
                );
                return false;
            }

            if ($user->getTokenDate() < new DateTime()) {
                $form->addError(
                    new FormError(
                        'Vous avez dépassé le délai pour la récupération du mot de passe.
                        Merci de réitérer votre demande.'
                    )
                );
                return false;
            }

            $dto = $form->getData();

            $user->changePassword(
                $this->encoder->encodePassword(
                    $user,
                    $dto->password
                )
            );
            $user->deleteToken();

            $this->repository->saveUser($user);

            return true;
        }

        return false;
    }
}
