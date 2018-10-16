<?php

namespace App\Tests\UI\Actions\User;

use App\Application\Handlers\Interfaces\Forms\User\ProfilePictureHandlerInterface;
use App\Tests\Fixtures\Traits\PictureAndVideoFixtures;
use App\UI\Actions\User\ProfilePictureAction;
use App\UI\Presenters\User\ProfilePicturePresenter;
use App\UI\Responders\User\ProfilePictureResponder;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;
use Twig\Environment;

class ProfilePictureActionTest extends TestCase
{
    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testTheReturnResponseOfAction()
    {
        $this->constructPicturesAndVideos();

        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturn('vue de la page');
        $presenter = new ProfilePicturePresenter($twig);
        $responder = new ProfilePictureResponder($presenter);

        $form = $this->createMock(FormInterface::class);
        $form->method('handleRequest')->willReturnSelf();
        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formFactory->method('create')->willReturn($form);

        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($this->johnDoe);
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->method('getToken')->willReturn($token);

        $handler = $this->createMock(ProfilePictureHandlerInterface::class);

        $tokenGenerator = new UriSafeTokenGenerator();

        $session = new Session(new MockArraySessionStorage());
        $request = new Request();
        $request->setSession($session);

        $action = new ProfilePictureAction(
            $responder,
            $formFactory,
            $tokenStorage,
            $handler,
            $tokenGenerator
        );

        $response = $action->profilePicture($request);

        self::assertNotInstanceOf(RedirectResponse::class, $response);
        self::assertInstanceOf(Response::class, $response);
    }

    use PictureAndVideoFixtures;
}
