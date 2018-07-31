<?php

namespace App\Application\Listener\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class NoAuthenticationCheckerListener
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * AuthenticatorCheckerListener constructor.
     * @param UrlGeneratorInterface $urlGenerator
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $noConnectedPaths = [
            $this->urlGenerator->generate('Authentication_user_connection'),
            $this->urlGenerator->generate('Authentication_user_registration'),
            $this->urlGenerator->generate('Authentication_password_recovery_username'),
        ];

        foreach ($noConnectedPaths as $noConnectedPath) {
            if (preg_match("#{$noConnectedPath}#", $request->getUri())) {
                if ($this->authorizationChecker->isGranted('ROLE_USER')) {
                    $event->setResponse(
                        new RedirectResponse(
                            $this->urlGenerator->generate('home')
                        )
                    );
                }
            }
        }
    }
}
