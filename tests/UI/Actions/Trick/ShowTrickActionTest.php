<?php

namespace App\Tests\UI\Actions\Trick;

use App\Application\FormFactory\Interfaces\CommentModificationFormFactoryInterface;
use App\Application\Handlers\Interfaces\Forms\Comment\AddCommentHandlerInterface;
use App\Application\Helpers\Interfaces\PaginationHelperInterface;
use App\Domain\Repository\CommentRepository;
use App\Domain\Repository\TrickRepository;
use App\Tests\Fixtures\Traits\TrickAndCategoryFixtures;
use App\UI\Actions\Trick\ShowTrickAction;
use App\UI\Presenters\Trick\ShowTrickPresenter;
use App\UI\Responders\Trick\ShowTrickResponder;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class ShowTrickActionTest extends TestCase
{
    /**
     * @var ShowTrickAction
     */
    private $action;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var AddCommentHandlerInterface|MockObject
     */
    private $handler;

    /**
     * @var TrickRepository|MockObject
     */
    private $trickRepository;

    /**
     * @var CommentRepository|MockObject
     */
    private $commentRepository;

    /**
     * @var PaginationHelperInterface|MockObject
     */
    private $paginationHelper;

    public function setUp()
    {
        $this->constructCategoryAndTrick();

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/url');

        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturn('vue de la page');
        $presenter = new ShowTrickPresenter($twig);
        $responder = new ShowTrickResponder($presenter, $urlGenerator);

        $this->trickRepository = $this->createMock(TrickRepository::class);
        $this->commentRepository = $this->createMock(CommentRepository::class);

        $form = $this->createMock(FormInterface::class);
        $form->method('handleRequest')->willReturnSelf();
        $formFactory = $this->createMock(CommentModificationFormFactoryInterface::class);
        $formFactory->method('create')->willReturn($form);

        $this->handler = $this->createMock(AddCommentHandlerInterface::class);

        $this->paginationHelper = $this->createMock(PaginationHelperInterface::class);

        $this->request = new Request();

        $this->action = new ShowTrickAction(
            $this->trickRepository,
            $responder,
            $formFactory,
            $this->handler,
            $this->commentRepository,
            $this->paginationHelper
        );
    }

    use TrickAndCategoryFixtures;

    /**
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testRedirectResponseIfSlugForEntityIsWrong()
    {
        $this->trickRepository->method('loadOneTrickWithCategoryAndAuthor')
                              ->willReturn(null);

        self::assertInstanceOf(
            RedirectResponse::class,
            $this->action->showTrick($this->request, 'badSlug', 1)
        );
    }

    /**
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testRedirectResponseIfCommentIsSubmitted()
    {
        $this->trickRepository->method('loadOneTrickWithCategoryAndAuthor')
                              ->willReturn($this->mute);

        $this->handler->method('handle')->willReturn(true);

        self::assertInstanceOf(
            RedirectResponse::class,
            $this->action->showTrick($this->request, 'goodSlug', 1)
        );
    }

    /**
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testRedirectResponseIfPageNumberIsNull()
    {
        $this->trickRepository->method('loadOneTrickWithCategoryAndAuthor')
                              ->willReturn($this->mute);

        $this->handler->method('handle')->willReturn(false);

        $this->paginationHelper->method('pagination')->willReturn(null);

        self::assertInstanceOf(
            RedirectResponse::class,
            $this->action->showTrick($this->request, 'goodSlug', 1)
        );
    }

    /**
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testResponseIfSlugForEntityIsGoodCommentNotSubmittedAndPaginationIsCorrect()
    {
        $this->trickRepository->method('loadOneTrickWithCategoryAndAuthor')
                              ->willReturn($this->mute);

        $this->handler->method('handle')->willReturn(false);

        $this->paginationHelper->method('pagination')->willReturn(1);

        $response = $this->action->showTrick($this->request, 'goodSlug', 1);

        self::assertInstanceOf(Response::class, $response);
        self::assertNotInstanceOf(RedirectResponse::class, $response);
    }
}
