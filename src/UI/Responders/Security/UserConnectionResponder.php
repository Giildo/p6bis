<?php

namespace App\UI\Responders\Security;

use App\UI\Presenters\Interfaces\Security\UserConnectionPresenterInterface;
use App\UI\Responders\Interfaces\Security\UserConnectionResponderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserConnectionResponder implements UserConnectionResponderInterface
{
    /**
     * @var UserConnectionPresenterInterface
     */
    private $presenter;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * UserConnectionResponder constructor.
     * @param UserConnectionPresenterInterface $presenter
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        UserConnectionPresenterInterface $presenter,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->presenter = $presenter;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function userConnectionResponse(
        FormInterface $form
    ): Response {
        return new Response(
            $this->presenter->userConnectionPresentation(
                $form
            )
        );
    }
}
