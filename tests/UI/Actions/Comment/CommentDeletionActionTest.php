<?php

namespace App\Tests\UI\Actions\Comment;

use App\Domain\Model\Category;
use App\Domain\Model\Comment;
use App\Domain\Model\Interfaces\CommentInterface;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use App\Domain\Repository\CommentRepository;
use App\UI\Actions\Comment\CommentDeletionAction;
use App\UI\Responders\Comment\CommentDeletionResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CommentDeletionActionTest extends TestCase
{
    private $action;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var CommentInterface
     */
    private $comment;

    protected function setUp()
    {
        $repository = $this->createMock(CommentRepository::class);

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/url');
        $responder = new CommentDeletionResponder($urlGenerator);

        $this->action = new CommentDeletionAction($repository, $responder);

        $session = new Session(new MockArraySessionStorage());
        $this->request = new Request();
        $this->request->setSession($session);

        $user = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john@doe.fr',
            '12345678'
        );

        $category = new Category(
            'grab',
            'Grab'
        );

        $trick = new Trick(
            'mute',
            'Mute',
            'Description de la figure',
            $category,
            $user
        );

        $this->comment = new Comment(
            'Commentaire simulé.',
            $trick,
            $user
        );
    }

    public function testConstructor()
    {
        self::assertInstanceOf(CommentDeletionAction::class, $this->action);
    }

    public function testTheRedirectResponseIfTheSessionHasNoComment()
    {
        $response = $this->action->commentDeletion($this->request, 'trickSlug', 'id');

        self::assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testTheRedirectResponseIfTheSessionHasComment()
    {
        $session = $this->request->getSession();
        $session->set('comment', $this->comment);
        $this->request->setSession($session);

        $response = $this->action->commentDeletion($this->request, 'trickSlug', 'id');

        self::assertInstanceOf(RedirectResponse::class, $response);
    }
}
