<?php

namespace App\Application\Listener\Trick;

use App\Domain\Repository\TrickRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class RoleUserListener
{
    /**
     * @var TrickRepository
     */
    private $trickRepository;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var AuthorizationCheckerInterface
     */
    private $checker;

    /**
     * RoleUserListener constructor.
     * @param TrickRepository $trickRepository
     * @param UrlGeneratorInterface $urlGenerator
     * @param TokenStorageInterface $tokenStorage
     * @param AuthorizationCheckerInterface $checker
     */
    public function __construct(
        TrickRepository $trickRepository,
        UrlGeneratorInterface $urlGenerator,
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $checker
    ) {
        $this->trickRepository = $trickRepository;
        $this->urlGenerator = $urlGenerator;
        $this->tokenStorage = $tokenStorage;
        $this->checker = $checker;
    }

    /**
     * @param GetResponseEvent $event
     *
     * @throws NonUniqueResultException
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (!is_null($trickSlug = $request->attributes->get('trickSlug'))) {
            $secureURIs = [
                $this->urlGenerator->generate(
                    'Trick_modification',
                    ['trickSlug' => $trickSlug]
                ),
                $this->urlGenerator->generate(
                    'Trick_deletion',
                    ['trickSlug' => $trickSlug]
                ),
            ];

            foreach ($secureURIs as $uri) {
                if (preg_match("#{$uri}#", $request->getUri())) {
                    $trick = $this->trickRepository->loadOneTrickWithCategoryAndAuthor(
                        $trickSlug
                    );

                    if (is_null($trick)) {
                        $event->setResponse(
                            new RedirectResponse(
                                $this->urlGenerator->generate('Home')
                            )
                        );
                        return;
                    }

                    $userConnected = $this->tokenStorage->getToken()
                                                        ->getUser()
                    ;

                    if (
                        ($this->checker->isGranted(
                                'ROLE_USER'
                            ) && $trick->getAuthor() === $userConnected) ||
                        ($this->checker->isGranted('ROLE_ADMIN'))
                    ) {
                        $request->getSession()
                                ->set('trick', $trick)
                        ;
                        return;
                    }

                    $event->setResponse(
                        new RedirectResponse(
                            $this->urlGenerator->generate('Home')
                        )
                    );
                    return;
                }
            }
        }

        return;
    }
}
