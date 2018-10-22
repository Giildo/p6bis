<?php

namespace App\Application\Handlers\Forms\Security;

use App\Application\Events\Core\FlashMessageEvent;
use App\Application\Handlers\Interfaces\Forms\Security\UserRegistrationHandlerInterface;
use App\Domain\Builders\Interfaces\UserBuilderInterface;
use App\Domain\Repository\UserRepository;
use Doctrine\ORM\ORMException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * UserRegistrationHandler constructor.
     * @param UserBuilderInterface $builder
     * @param UserRepository $repository
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        UserBuilderInterface $builder,
        UserRepository $repository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->builder = $builder;
        $this->repository = $repository;
        $this->eventDispatcher = $eventDispatcher;
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
            $user = $this->builder->build($form->getData())
                                  ->getUser();

            $this->repository->saveUser($user);

            $flashMessage = new FlashMessageEvent(
                true,
                'L\'enregistrement a été effectué avec succès, veuillez vous connecter.'
            );

            $this->eventDispatcher->dispatch(
                FlashMessageEvent::FLASH_MESSAGE,
                $flashMessage
            );

            return true;
        }

        return false;
    }
}
