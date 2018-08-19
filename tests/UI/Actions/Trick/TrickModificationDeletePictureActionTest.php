<?php

namespace App\Tests\UI\Actions\Trick;

use App\Domain\Model\Interfaces\PictureInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Picture;
use App\Domain\Repository\PictureRepository;
use App\UI\Actions\Trick\TrickModificationDeletePictureAction;
use App\UI\Responders\Trick\TrickModificationDeleteVideoOrPictureResponder;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TrickModificationDeletePictureActionTest extends TestCase
{

    public function testTheAction()
    {
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/url');

        $responder = new TrickModificationDeleteVideoOrPictureResponder($urlGenerator);

        $trick = $this->createMock(TrickInterface::class);
        $trick->method('getSlug')->willReturn('Slug');
        $picture = $this->createMock(PictureInterface::class);
        $picture->method('getTrick')->willReturn($trick);
        $picture->method('getExtension')->willReturn('jpeg');
        $picture->method('getName')->willReturn('mute20180812125412_1');
        $repository = $this->createMock(PictureRepository::class);
        $repository->method('loadOnePictureWithName')->willReturn($picture);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('getRepository')->willReturn($repository);

        $request = new Request();
        $request->query->set('s', 'mute20180812125412_1');
        $request->query->set('t', 'token1234567890');

        $session = new Session(new MockArraySessionStorage());
        $session->set('tokens', ['mute20180812125412_1' => 'token1234567890']);

        $action = new TrickModificationDeletePictureAction(
            $responder,
            $entityManager->getRepository(Picture::class),
            $session
        );

        self::assertInstanceOf(TrickModificationDeletePictureAction::class, $action);

        $file = fopen(__DIR__ . '/../../../../public/pic/tricks/mute20180812125412_1.jpeg', 'w+');
        fclose($file);

        $response = $action->deletePicture($request);

        self::assertInstanceOf(RedirectResponse::class, $response);
    }
}
