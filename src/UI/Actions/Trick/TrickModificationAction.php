<?php

namespace App\UI\Actions\Trick;

use App\Application\Handlers\Interfaces\Forms\Trick\TrickModificationHandlerInterface;
use App\Application\Helpers\Interfaces\PictureAndVideoTokenManagerInterface;
use App\Domain\DTO\Trick\TrickModificationDTO;
use App\Domain\Model\Interfaces\TrickInterface;
use App\UI\Forms\Trick\TrickModificationType;
use App\UI\Responders\Interfaces\Trick\TrickModificationResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

class TrickModificationAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var TrickModificationResponderInterface
     */
    private $responder;
    /**
     * @var TrickModificationHandlerInterface
     */
    private $handler;
    /**
     * @var PictureAndVideoTokenManagerInterface
     */
    private $tokenManager;

    /**
     * TrickModificationAction constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param TrickModificationResponderInterface $responder
     * @param TrickModificationHandlerInterface $handler
     * @param PictureAndVideoTokenManagerInterface $tokenManager
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        TrickModificationResponderInterface $responder,
        TrickModificationHandlerInterface $handler,
        PictureAndVideoTokenManagerInterface $tokenManager
    ) {
        $this->formFactory = $formFactory;
        $this->responder = $responder;
        $this->handler = $handler;
        $this->tokenManager = $tokenManager;
    }

    /**
     * @Route(
     *     path="/espace-utilisateur/trick/modification/{trickSlug}",
     *     name="Trick_modification",
     *     requirements={"trickSlug"="[a-zA-Z0-9-]+"}
     * )
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function modification(Request $request): Response
    {
        /** @var TrickInterface $trick */
        $trick = $request->getSession()
                         ->remove('trick')
        ;

        $dto = new TrickModificationDTO(
            $trick->getDescription(),
            $trick->isPublished(),
            $trick->getCategory(),
            [],
            []
        );

        $form = $this->formFactory->create(TrickModificationType::class, $dto)
                                  ->handleRequest($request)
        ;

        if ($this->handler->handle($form, $trick)) {
            return $this->responder->trickModificationResponse(
                true,
                'Trick_show',
                ['trickSlug' => $trick->getSlug()]
            );
        }

        $tokens = $this->tokenManager->createTokens(
            $trick->getPictures(), $trick->getVideos()
        );

        $request->getSession()
                ->set('tokens', $tokens)
        ;

        return $this->responder->trickModificationResponse(
            false, '', [], $form, $trick
        );
    }
}
