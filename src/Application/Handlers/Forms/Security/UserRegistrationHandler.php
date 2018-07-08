<?php

namespace App\Application\Handlers\Forms\Security;

use App\Application\Handlers\Interfaces\Forms\Security\UserRegistrationHandlerInterface;
use App\Domain\Builders\Interfaces\UserBuilderInterface;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use Doctrine\ORM\ORMException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserRegistrationHandler implements UserRegistrationHandlerInterface
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoder;
    /**
     * @var UserBuilderInterface
     */
    private $builder;
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * UserRegistrationHandler constructor.
     * @param EncoderFactoryInterface $encoder
     * @param UserBuilderInterface $builder
     * @param UserRepository $repository
     */
    public function __construct(
        EncoderFactoryInterface $encoder,
        UserBuilderInterface $builder,
        UserRepository $repository
    ) {
        $this->encoder = $encoder;
        $this->builder = $builder;
        $this->repository = $repository;
    }

    /**
     * @param FormInterface $form
     *
     * @throws ORMException
     *
     * @return bool
     */
    public function handle(FormInterface $form): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $encoder = $this->encoder->getEncoder(User::class);

            $userDTO = $form->getData();

            $this->builder->createUserFromRegistration($userDTO, $encoder);

            $this->repository->saveUserFromRegistration($this->builder->getUser());

            return true;
        }

        return false;
    }
}
