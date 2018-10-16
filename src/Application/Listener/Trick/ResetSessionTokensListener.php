<?php

namespace App\Application\Listener\Trick;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ResetSessionTokensListener
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * ResetSessionTokensListener constructor.
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        UrlGeneratorInterface $urlGenerator
    ) {
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

            if (!empty($request->getSession()->get('tokens'))) {
                $request->getSession()->set('tokens', []);
            }

            return;
        }

        return;
    }
}
