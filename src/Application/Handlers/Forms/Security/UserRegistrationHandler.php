<?php

namespace App\Application\Handlers\Forms\Security;

use App\Application\Handlers\Interfaces\Forms\Security\UserRegistrationHandlerInterface;
use App\Domain\Builders\Interfaces\UserBuilderInterface;
use App\Domain\Repository\UserRepository;
use Doctrine\ORM\ORMException;
use Symfony\Component\Form\FormInterface;

class UserRegistrationHandler implements UserRegistrationHandlerInterface
{
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
     * @param UserBuilderInterface $builder
     * @param UserRepository $repository
     */
    public function __construct(
        UserBuilderInterface $builder,
        UserRepository $repository
    ) {
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
            $user = $this->builder->createUser($form->getData())
                                  ->getUser();

            $this->repository->saveUser($user);

            return true;
        }

        return false;
    }
}
