<?php

namespace App\UI\Responders\Security;

use App\UI\Presenters\Interfaces\Security\UserConnectionPresenterInterface;
use App\UI\Responders\Interfaces\Security\UserConnectionResponderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

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
        ?FormInterface $form = null,
        ?AuthenticationException $error = null,
        ?string $lastUserConnected = ''
    ): Response {
        return (is_null($form)) ?
            new RedirectResponse($this->urlGenerator->generate('Home')):
            new Response(
                $this->presenter->userConnectionPresentation(
                    $form,
                    $error
                )
            );
    }
}
