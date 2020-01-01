<?php

namespace App\Tests\UI\Actions\Trick;

use App\Domain\Repository\CommentRepository;
use App\Domain\Repository\TrickRepository;
use App\Tests\Fixtures\Traits\CommentFixtures;
use App\UI\Actions\Trick\TrickDeletionAction;
use App\UI\Responders\Trick\TrickDeletionResponder;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TrickDeletionActionTest extends TestCase
{
    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testIfReturnActionIsResponse()
    {
        $this->constructComments();

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/url');
        $responder = new TrickDeletionResponder($urlGenerator);

        $flashBag = new FlashBag();

        $trickRepository = $this->createMock(TrickRepository::class);
        $commentRepository = $this->createMock(CommentRepository::class);
        $commentRepository->method('loadAllCommentsOfATrick')->willReturn(
            [
                $this->comment1,
                $this->comment2,
            ]
        );

        $action = new TrickDeletionAction(
            $trickRepository,
            $commentRepository,
            $responder,
            $flashBag
        );

        $session = new Session(new MockArraySessionStorage());
        $session->set('trick', $this->mute);
        $request = new Request();
        $request->setSession($session);

        $response = $action->delete($request);

        self::assertInstanceOf(RedirectResponse::class, $response);
    }

    use CommentFixtures;
}
