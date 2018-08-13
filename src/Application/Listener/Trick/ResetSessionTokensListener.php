<?php

namespace App\Application\Listener\Trick;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ResetSessionTokensListener
{
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * ResetSessionTokensListener constructor.
     * @param SessionInterface $session
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        SessionInterface $session,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->session = $session;
        $this->urlGenerator = $urlGenerator;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($event->isMasterRequest()) {
            $request = $event->getRequest();

            $noResetTokenPaths = [
                $this->urlGenerator->generate('Trick_modification_delete_picture'),
                $this->urlGenerator->generate('Trick_modification_delete_video'),
            ];

            foreach ($noResetTokenPaths as $noResetTokenPath) {
                if (preg_match("#\_wdt#", $request->getUri())) {
                    return;
                }

                if (preg_match("#{$noResetTokenPath}#", $request->getUri())) {
                    return;
                }
            }

            if (!empty($this->session->get('tokens'))) {
                $this->session->set('tokens', []);
            }

            return;
        }

        return;
    }
}
