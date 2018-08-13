<?php

namespace App\Tests\UI\Actions\Trick;

use App\Application\Handlers\Interfaces\Forms\Trick\TrickModificationHandlerInterface;
use App\Application\Helpers\PictureAndVideoTokenManager;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Repository\TrickRepository;
use App\UI\Actions\Trick\TrickModificationAction;
use App\UI\Presenters\Trick\TrickModificationPresenter;
use App\UI\Responders\Trick\TrickModificationResponder;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Twig\Environment;

class TrickModificationActionTest extends TestCase
{
    private $request;

    private $repository;

    private $trick;

    private $handler;

    private $entityManager;

    private $formFactory;

    private $responder;

    private $tokenManager;

    private $session;

    protected function setUp()
    {
        $form = $this->createMock(FormInterface::class);
        $form->method('handleRequest')->willReturnSelf();
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->formFactory->method('create')->willReturn($form);

        $this->trick = $this->createMock(TrickInterface::class);
        $this->trick->method('getPictures')->willReturn([]);
        $this->trick->method('getVideos')->willReturn([]);
        $this->trick->method('getSlug')->willReturn('goodSlug');
        $this->repository = $this->createMock(TrickRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturn('Vue de la page');
        $presenter = new TrickModificationPresenter($twig);
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/url');
        $this->responder = new TrickModificationResponder(
            $presenter,
            $urlGenerator
        );

        $this->handler = $this->createMock(TrickModificationHandlerInterface::class);

        $tokenGenerator = $this->createMock(TokenGeneratorInterface::class);
        $this->tokenManager = new PictureAndVideoTokenManager($tokenGenerator);

        $this->session = $this->createMock(SessionInterface::class);

        $this->request = $this->createMock(Request::class);
    }

    public function testConstructor()
    {
        $action = new TrickModificationAction(
            $this->formFactory,
            $this->entityManager,
            $this->responder,
            $this->handler,
            $this->tokenManager,
            $this->session
        );

        self::assertInstanceOf(TrickModificationAction::class, $action);
    }

    public function testTheRedirectionResponseIfSlugIsWrong()
    {
        $this->repository->method('loadOneTrickWithCategoryAndAuthor')->willReturn(null);
        $this->entityManager->method('getRepository')->willReturn($this->repository);

        $action = new TrickModificationAction(
            $this->formFactory,
            $this->entityManager,
            $this->responder,
            $this->handler,
            $this->tokenManager,
            $this->session
        );

        $response = $action->modification($this->request, 'badSlug');

        self::assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testResponseIfHandlerReturnFalse()
    {
        $this->repository->method('loadOneTrickWithCategoryAndAuthor')->willReturn($this->trick);
        $this->entityManager->method('getRepository')->willReturn($this->repository);
        $this->handler->method('handle')->willReturn(false);

        $action = new TrickModificationAction(
            $this->formFactory,
            $this->entityManager,
            $this->responder,
            $this->handler,
            $this->tokenManager,
            $this->session
        );

        $response = $action->modification($this->request, 'goodSlug');

        self::assertInstanceOf(Response::class, $response);
        self::assertNotInstanceOf(RedirectResponse::class, $response);
    }

    public function testRedirectResponseIfHandlerReturnTrue()
    {
        $this->repository->method('loadOneTrickWithCategoryAndAuthor')->willReturn($this->trick);
        $this->entityManager->method('getRepository')->willReturn($this->repository);
        $this->handler->method('handle')->willReturn(true);

        $action = new TrickModificationAction(
            $this->formFactory,
            $this->entityManager,
            $this->responder,
            $this->handler,
            $this->tokenManager,
            $this->session
        );

        $response = $action->modification($this->request, 'goodSlug');

        self::assertInstanceOf(RedirectResponse::class, $response);
    }
}
