<?php

namespace App\Application\Handlers\Forms\Security;

use App\Application\Handlers\Interfaces\Forms\Security\UserRegistrationHandlerInterface;
use App\Domain\Builders\Interfaces\UserBuilderInterface;
use App\Domain\Model\User;
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
     * UserRegistrationHandler constructor.
     * @param EncoderFactoryInterface $encoder
     * @param UserBuilderInterface $builder
     */
    public function __construct(
        EncoderFactoryInterface $encoder,
        UserBuilderInterface $builder
    ) {
        $this->encoder = $encoder;
        $this->builder = $builder;
    }

    /**
     * @param FormInterface $form
     * @return bool
     */
    public function handle(FormInterface $form): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $encoder = $this->encoder->getEncoder(User::class);

            $userDTO = $form->getData();

            $this->builder->createUserFromRegistration($userDTO, $encoder);

            // TODO To save the entity in database

            return true;
        }

        return false;
    }
}
