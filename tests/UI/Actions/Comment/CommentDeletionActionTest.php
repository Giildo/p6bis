<?php

namespace App\Tests\UI\Actions\Comment;

use App\Domain\Repository\CommentRepository;
use App\Tests\Fixtures\Traits\CommentFixtures;
use App\UI\Actions\Comment\CommentDeletionAction;
use App\UI\Responders\Comment\CommentDeletionResponder;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CommentDeletionActionTest extends TestCase
{
    /**
     * @var CommentDeletionAction
     */
    private $action;

    /**
     * @var Request
     */
    private $request;

    protected function setUp()
    {
        $this->constructComments();

        $repository = $this->createMock(CommentRepository::class);

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/url');
        $responder = new CommentDeletionResponder($urlGenerator);

        $this->action = new CommentDeletionAction($repository, $responder);

        $session = new Session(new MockArraySessionStorage());
        $this->request = new Request();
        $this->request->setSession($session);
    }

    use CommentFixtures;

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testTheRedirectResponseIfTheSessionHasNoComment()
    {
        self::assertInstanceOf(
            RedirectResponse::class,
            $this->action->commentDeletion($this->request, 'trickSlug')
        );
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testTheRedirectResponseIfTheSessionHasComment()
    {
        $session = $this->request->getSession();
        $session->set('comment', $this->comment1);
        $this->request->setSession($session);

        self::assertInstanceOf(
            RedirectResponse::class,
            $this->action->commentDeletion($this->request, 'trickSlug')
        );
    }
}
