<?php

namespace App\UI\Actions\Security;

use App\Application\Handlers\Interfaces\Forms\Security\UserRegistrationHandlerInterface;
use App\UI\Forms\Security\UserRegistrationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class UserRegistrationAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formBuilder;
    /**
     * @var UserRegistrationHandlerInterface
     */
    private $handler;

    /**
     * UserRegistrationAction constructor.
     * @param FormFactoryInterface $formBuilder
     * @param UserRegistrationHandlerInterface $handler
     */
    public function __construct(
        FormFactoryInterface $formBuilder,
        UserRegistrationHandlerInterface $handler
    ) {
        $this->formBuilder = $formBuilder;
        $this->handler = $handler;
    }

    public function registration(Request $request)
    {
        $form = $this->formBuilder->create(UserRegistrationType::class)
                                  ->handleRequest($request);

        if ($this->handler->handle($form)) {
            // TODO redirection
        }

        // TODO response
    }
}
