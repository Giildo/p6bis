<?php

namespace App\Application\Listener\Comment;

use App\Domain\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class RoleUserListener
{
    /**
     * @var CommentRepository
     */
    private $commentRepository;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * RoleUserListener constructor.
     * @param CommentRepository $commentRepository
     * @param UrlGeneratorInterface $urlGenerator
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        CommentRepository $commentRepository,
        UrlGeneratorInterface $urlGenerator,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage
    ) {
        $this->commentRepository = $commentRepository;
        $this->urlGenerator = $urlGenerator;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param GetResponseEvent $event
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (!is_null($trickSlug = $request->attributes->get('trickSlug'))) {
            $URIs = [
                $this->urlGenerator->generate(
                    'Trick_show',
                    ['trickSlug' => $trickSlug]
                ),
                $this->urlGenerator->generate(
                    'Trick_show_comment_deletion',
                    ['trickSlug' => $trickSlug]
                )
            ];

            foreach ($URIs as $uri) {
                if (preg_match("#{$uri}#", $request->getUri())) {
                    $comment = null;

                    if (!is_null($request->attributes->get('id')) || (
                            !is_null($request->query->get('action')) && !is_null($request->query->get('id'))
                        )
                    ) {
                        $comment = $this->commentRepository->loadOneCommentWithHerId(
                            $request->query->get('id')
                        );

                        if (is_null($comment)) {
                            $event->setResponse(new RedirectResponse($uri));
                            return;
                        }

                        if ($this->authorizationChecker->isGranted(
                                'ROLE_ADMIN'
                            ) || (
                                $comment->getAuthor() ===
                                $this->tokenStorage->getToken()
                                                   ->getUser() && $this->authorizationChecker->isGranted(
                                    'ROLE_USER'
                                )
                            )
                        ) {
                            $request->getSession()
                                    ->set('comment', $comment)
                            ;
                            return;
                        }

                        $event->setResponse(new RedirectResponse($uri));
                        return;
                    }
                }
            }
        }

        return;
    }
}
