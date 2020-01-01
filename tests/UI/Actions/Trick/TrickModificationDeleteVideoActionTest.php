<?php

namespace App\Tests\UI\Actions\Trick;

use App\Domain\Model\Interfaces\VideoInterface;
use App\Domain\Model\Video;
use App\Domain\Repository\VideoRepository;
use App\Tests\Fixtures\Traits\TrickAndCategoryFixtures;
use App\UI\Actions\Trick\TrickModificationDeleteVideoAction;
use App\UI\Responders\Trick\TrickModificationDeleteVideoOrPictureResponder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TrickModificationDeleteVideoActionTest extends TestCase
{
    /**
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testTheAction()
    {
        $this->constructCategoryAndTrick();

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/url');

        $responder = new TrickModificationDeleteVideoOrPictureResponder($urlGenerator);

        $video = $this->createMock(VideoInterface::class);
        $video->method('getTrick')->willReturn($this->mute);
        $repository = $this->createMock(VideoRepository::class);
        $repository->method('loadOneVideoWithName')->willReturn($video);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('getRepository')->willReturn($repository);

        $session = new Session(new MockArraySessionStorage());
        $session->set('tokens', ['B3Lhid5_HJc' => 'token1234567890']);
        $request = new Request();
        $request->query->set('s', 'B3Lhid5_HJc');
        $request->query->set('t', 'token1234567890');
        $request->setSession($session);

        $action = new TrickModificationDeleteVideoAction(
            $responder,
            $entityManager->getRepository(Video::class)
        );

        self::assertInstanceOf(
            RedirectResponse::class,
            $action->deleteVideo($request)
        );
    }

    use TrickAndCategoryFixtures;
}
