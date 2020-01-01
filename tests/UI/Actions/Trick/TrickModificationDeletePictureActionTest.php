<?php

namespace App\Tests\UI\Actions\Trick;

use App\Domain\Model\Interfaces\PictureInterface;
use App\Domain\Model\Picture;
use App\Domain\Repository\PictureRepository;
use App\Tests\Fixtures\Traits\TrickAndCategoryFixtures;
use App\UI\Actions\Trick\TrickModificationDeletePictureAction;
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

class TrickModificationDeletePictureActionTest extends TestCase
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

        $picture = $this->createMock(PictureInterface::class);
        $picture->method('getTrick')->willReturn($this->mute);
        $picture->method('getExtension')->willReturn('jpeg');
        $picture->method('getName')->willReturn('mute20180812125412_1');
        $repository = $this->createMock(PictureRepository::class);
        $repository->method('loadOnePictureWithName')->willReturn($picture);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('getRepository')->willReturn($repository);

        $session = new Session(new MockArraySessionStorage());
        $session->set('tokens', ['mute20180812125412_1' => 'token1234567890']);
        $request = new Request();
        $request->query->set('s', 'mute20180812125412_1');
        $request->query->set('t', 'token1234567890');
        $request->setSession($session);

        $action = new TrickModificationDeletePictureAction(
            $responder,
            $entityManager->getRepository(Picture::class)
        );

        $file = fopen(__DIR__ . '/../../../../public/pic/tricks/mute20180812125412_1.jpeg', 'w+');
        fclose($file);

        self::assertInstanceOf(
            RedirectResponse::class,
            $action->deletePicture($request)
        );
    }

    use TrickAndCategoryFixtures;
}
