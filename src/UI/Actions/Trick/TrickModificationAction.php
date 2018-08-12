<?php

namespace App\UI\Actions\Trick;

use App\Application\Handlers\Interfaces\Forms\Trick\TrickModificationHandlerInterface;
use App\Application\Helpers\PictureAndVideoTokenManager;
use App\Domain\DTO\Trick\TrickModificationDTO;
use App\Domain\Model\Picture;
use App\Domain\Model\Trick;
use App\Domain\Repository\TrickRepository;
use App\UI\Forms\Trick\TrickModificationType;
use App\UI\Responders\Interfaces\Trick\TrickModificationResponderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class TrickModificationAction
{
	/**
	 * @var FormFactoryInterface
	 */
	private $formFactory;
	/**
	 * @var TrickRepository
	 */
	private $repository;
	/**
	 * @var TrickModificationResponderInterface
	 */
	private $responder;
	/**
	 * @var EntityManagerInterface
	 */
	private $entityManager;
	/**
	 * @var TrickModificationHandlerInterface
	 */
	private $handler;
    /**
     * @var PictureAndVideoTokenManager
     */
    private $tokenManager;
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * TrickModificationAction constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param EntityManagerInterface $entityManager
     * @param TrickModificationResponderInterface $responder
     * @param TrickModificationHandlerInterface $handler
     * @param PictureAndVideoTokenManager $tokenManager
     * @param SessionInterface $session
     */
	public function __construct(
		FormFactoryInterface $formFactory,
		EntityManagerInterface $entityManager,
		TrickModificationResponderInterface $responder,
		TrickModificationHandlerInterface $handler,
        PictureAndVideoTokenManager $tokenManager,
        SessionInterface $session
	) {
		$this->formFactory = $formFactory;
		$this->repository = $entityManager->getRepository(Trick::class);
		$this->responder = $responder;
		$this->entityManager = $entityManager;
		$this->handler = $handler;
        $this->tokenManager = $tokenManager;
        $this->session = $session;
    }

	/**
	 * @Route(
	 *     path="/espace-utilisateur/trick/modification/{trickSlug}",
	 *     name="trick_modification",
	 *     requirements={"trickSlug"="\w+"}
	 * )
	 *
	 * @param Request $request
	 * @param string $trickSlug
	 *
	 * @return Response
	 *
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function modification(Request $request, string $trickSlug) {
		$trick = $this->repository->loadOneTrickWithCategoryAndAuthor($trickSlug);

		if (is_null($trick)) {
			return $this->responder->trickModificationResponse();
		}

		$dto = new TrickModificationDTO(
			$trick->getDescription(),
			$trick->isPublished(),
			$trick->getCategory(),
			[],
			[]
		);

		$form = $this->formFactory->create(TrickModificationType::class, $dto)
								  ->handleRequest($request);

		if ($this->handler->handle($form, $trickSlug)) {
			return $this->responder->trickModificationResponse(
				true,
				'trick_show',
				['trickSlug' => $trick->getSlug()]
			);
		}

		$tokens = $this->tokenManager->createTokens($trick->getPictures(), $trick->getVideos());

		$this->session->set('tokens', $tokens);

		return $this->responder->trickModificationResponse(false, '', [], $form, $trick);
	}
}
