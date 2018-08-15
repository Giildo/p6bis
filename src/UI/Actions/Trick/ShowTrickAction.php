<?php

namespace App\UI\Actions\Trick;

use App\Application\Handlers\Interfaces\Forms\Comment\AddCommentHandlerInterface;
use App\Domain\Model\Trick;
use App\UI\Forms\Comment\AddCommentType;
use App\UI\Responders\Interfaces\Trick\ShowTrickResponderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowTrickAction
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ShowTrickResponderInterface
     */
    private $responder;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var AddCommentHandlerInterface
     */
    private $handler;

    /**
     * ShowTrickAction constructor.
     * @param EntityManagerInterface $entityManager
     * @param ShowTrickResponderInterface $responder
     * @param FormFactoryInterface $formFactory
     * @param AddCommentHandlerInterface $handler
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ShowTrickResponderInterface $responder,
        FormFactoryInterface $formFactory,
        AddCommentHandlerInterface $handler
    ) {
        $this->entityManager = $entityManager;
        $this->responder = $responder;
        $this->formFactory = $formFactory;
        $this->handler = $handler;
    }

    /**
     * @Route(path="/trick/{trickSlug}", name="Trick_show", requirements={"trickSlug"="\w+"})
     *
     * @param Request $request
     * @param string $trickSlug
     *
     * @return RedirectResponse|Response
     *
     * @throws NonUniqueResultException
     */
    public function showTrick(Request $request, string $trickSlug)
    {
        $trick = $this->entityManager
                      ->getRepository(Trick::class)
                      ->loadOneTrickWithCategoryAndAuthor($trickSlug);

        if (is_null($trick)) {
            return $this->responder->showTrickResponse();
        }

        $formComment = $this->formFactory->create(AddCommentType::class)
                                  ->handleRequest($request);

        $this->handler->handle($formComment, $trick);

        return $this->responder->showTrickResponse(
            false,
            $trick,
            $formComment
        );
    }
}
