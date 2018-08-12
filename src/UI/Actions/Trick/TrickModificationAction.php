<?php

namespace App\UI\Actions\Trick;

use App\Application\Handlers\Interfaces\Forms\Trick\TrickModificationHandlerInterface;
use App\Domain\DTO\Trick\NewTrickPictureDTO;
use App\Domain\DTO\Trick\NewTrickVideoDTO;
use App\Domain\DTO\Trick\TrickModificationDTO;
use App\Domain\DTO\Trick\TrickModificationPictureDTO;
use App\Domain\Model\Picture;
use App\Domain\Model\Trick;
use App\Domain\Model\Video;
use App\Domain\Repository\TrickRepository;
use App\UI\Forms\Trick\TrickModificationType;
use App\UI\Responders\Interfaces\Trick\TrickModificationResponderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

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
	 * @var TokenGeneratorInterface
	 */
	private $tokenGenerator;

	/**
	 * TrickModificationAction constructor.
	 *
	 * @param FormFactoryInterface $formFactory
	 * @param EntityManagerInterface $entityManager
	 * @param TrickModificationResponderInterface $responder
	 * @param TrickModificationHandlerInterface $handler
	 * @param TokenGeneratorInterface $tokenGenerator
	 */
	public function __construct(
		FormFactoryInterface $formFactory,
		EntityManagerInterface $entityManager,
		TrickModificationResponderInterface $responder,
		TrickModificationHandlerInterface $handler,
		TokenGeneratorInterface $tokenGenerator
	) {
		$this->formFactory = $formFactory;
		$this->repository = $entityManager->getRepository(Trick::class);
		$this->responder = $responder;
		$this->entityManager = $entityManager;
		$this->handler = $handler;
		$this->tokenGenerator = $tokenGenerator;
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

		if (!empty($trick->getPictures())) {
			/** @var Picture $picture */
			foreach ($trick->getPictures() as $picture) {
				$picture->createToken($this->tokenGenerator);
			}
		}

		$this->entityManager->flush();

		return $this->responder->trickModificationResponse(false, '', [], $form, $trick);
	}
}
