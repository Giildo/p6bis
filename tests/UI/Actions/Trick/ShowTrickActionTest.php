<?php

namespace App\Tests\UI\Actions\Trick;

use App\Application\Handlers\Interfaces\Forms\Comment\AddCommentHandlerInterface;
use App\Domain\Model\Category;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use App\Domain\Repository\TrickRepository;
use App\UI\Actions\Trick\ShowTrickAction;
use App\UI\Presenters\Interfaces\Trick\ShowTrickPresenterInterface;
use App\UI\Responders\Trick\ShowTrickResponder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ShowTrickActionTest extends TestCase
{
    private $action;

    /**
     * @var MockObject
     */
    private $request;

    /**
     * @var MockObject
     */
    private $handler;

    /**
     * @var MockObject
     */
    private $trickRepository;

    /**
     * @var TrickInterface
     */
    private $trick;

    public function setUp()
    {
        $presenter = $this->createMock(ShowTrickPresenterInterface::class);
        $presenter->method('showTrickPresentation')->willReturn('Vue de la page');

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('url');

        $responder = new ShowTrickResponder($presenter, $urlGenerator);

        $user = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john@doe.fr',
            '12345678'
        );

        $category = new Category(
            'gras',
            'Grabs'
        );

        $this->trick = new Trick(
            'mute',
            'Mute',
            'Figure de snowboard',
            $category,
            $user
        );

        $this->trickRepository = $this->createMock(TrickRepository::class);

        $form = $this->createMock(FormInterface::class);
        $form->method('handleRequest')->willReturnSelf();
        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formFactory->method('create')->willReturn($form);

        $this->handler = $this->createMock(AddCommentHandlerInterface::class);

        $this->action = new ShowTrickAction($this->trickRepository, $responder, $formFactory, $this->handler);

        $this->request = $this->createMock(Request::class);
    }

    public function testRedirectResponseIfSlugForEntityIsWrong()
    {
        $this->trickRepository->method('loadOneTrickWithCategoryAndAuthor')
                              ->willReturn(null);

        $response = $this->action->showTrick($this->request, 'badSlug');

        self::assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testRedirectResponseIfCommentIsSubmitted()
    {
        $this->trickRepository->method('loadOneTrickWithCategoryAndAuthor')
                              ->willReturn($this->trick);

        $this->handler->method('handle')->willReturn(true);

        $response = $this->action->showTrick($this->request, 'goodSlug');

        self::assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testResponseIfSlugForEntityIsGood()
    {
        $this->trickRepository->method('loadOneTrickWithCategoryAndAuthor')
                              ->willReturn($this->trick);

        $this->handler->method('handle')->willReturn(false);

        $response = $this->action->showTrick($this->request, 'goodSlug');

        self::assertInstanceOf(Response::class, $response);
        self::assertNotInstanceOf(RedirectResponse::class, $response);
    }
}
