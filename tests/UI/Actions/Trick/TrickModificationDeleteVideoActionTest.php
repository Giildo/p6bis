<?php

namespace App\Tests\UI\Actions\Trick;

use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Interfaces\VideoInterface;
use App\Domain\Model\Video;
use App\Domain\Repository\VideoRepository;
use App\UI\Actions\Trick\TrickModificationDeleteVideoAction;
use App\UI\Responders\Trick\TrickModificationDeleteVideoOrPictureResponder;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TrickModificationDeleteVideoActionTest extends TestCase
{
    public function testTheAction()
    {
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/url');

        $responder = new TrickModificationDeleteVideoOrPictureResponder($urlGenerator);

        $trick = $this->createMock(TrickInterface::class);
        $trick->method('getSlug')->willReturn('Slug');
        $video = $this->createMock(VideoInterface::class);
        $video->method('getTrick')->willReturn($trick);
        $repository = $this->createMock(VideoRepository::class);
        $repository->method('loadOneVideoWithName')->willReturn($video);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('getRepository')->willReturn($repository);

        $request = new Request();
        $request->query->set('s', 'B3Lhid5_HJc');
        $request->query->set('t', 'token1234567890');

        $session = new Session(new MockArraySessionStorage());
        $session->set('tokens', ['B3Lhid5_HJc' => 'token1234567890']);

        $action = new TrickModificationDeleteVideoAction(
            $responder,
            $entityManager->getRepository(Video::class),
            $session
        );

        self::assertInstanceOf(TrickModificationDeleteVideoAction::class, $action);

        $response = $action->deleteVideo($request);

        self::assertInstanceOf(RedirectResponse::class, $response);
    }
}
