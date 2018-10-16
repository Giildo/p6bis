<?php

namespace App\Tests\UI\Actions\User;

use App\Domain\Repository\PictureRepository;
use App\Tests\Fixtures\Traits\PictureAndVideoFixtures;
use App\UI\Actions\User\ProfilePictureDeletePictureAction;
use App\UI\Responders\User\ProfilePictureDeletePictureResponder;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProfilePictureDeletePictureActionTest extends TestCase
{
    /**
     * @throws NonUniqueResultException
     * @throws ORMException
     */
    public function testIfTheReturnIsARedirectResponse()
    {
        $this->constructPicturesAndVideos();

        $session = new Session(new MockArraySessionStorage());
        $session->set('tokenProfilePicture', 'token123456789');
        $request = new Request();
        $request->query->set('s', 'ProfilePicture123456789');
        $request->query->set('t', 'token123456789');
        $request->setSession($session);

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/url');
        $responder = new ProfilePictureDeletePictureResponder($urlGenerator);

        $repository = $this->createMock(PictureRepository::class);
        $repository->method('loadOnePictureWithName')->willReturn($this->pictureProfile);

        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($this->johnDoe);
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->method('getToken')->willReturn($token);

        $file = fopen(__DIR__ . '/../../../../public/pic/users/ProfilePicture123456789.jpeg', 'w+');
        fclose($file);

        $action = new ProfilePictureDeletePictureAction(
            $responder,
            $repository,
            $tokenStorage
        );

        self::assertInstanceOf(
            RedirectResponse::class,
            $action->deletePicture($request)
        );
    }

    use PictureAndVideoFixtures;
}
