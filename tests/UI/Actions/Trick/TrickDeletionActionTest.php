<?php

namespace App\Tests\UI\Actions\Trick;

use App\Domain\Model\Category;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use App\UI\Actions\Trick\TrickDeletionAction;
use App\UI\Responders\Trick\TrickDeletionResponder;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TrickDeletionActionTest extends KernelTestCase
{
    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    public function testAction()
    {
        $kernel = self::bootKernel();

        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropSchema($entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($entityManager->getMetadataFactory()->getAllMetadata());

        $category = new Category(
            'grab',
            'Grab'
        );

        $author = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john@doe.fr',
            '12345678'
        );

        $trick = new Trick(
            'mute',
            'Mute',
            'Description de l\'image.',
            $category,
            $author
        );

        $entityManager->persist($trick);
        $entityManager->flush();

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/url');
        $responder = new TrickDeletionResponder($urlGenerator);

        $action = new TrickDeletionAction(
            $entityManager,
            $responder
        );

        self::assertInstanceOf(TrickDeletionAction::class, $action);

        $trickLoaded = $entityManager->getRepository(Trick::class)
                               ->loadOneTrickWithCategoryAndAuthor('mute');
        $session = new Session(new MockArraySessionStorage());
        $session->set('trick', $trickLoaded);
        $request = new Request();
        $request->setSession($session);

        $response = $action->delete($request);

        self::assertInstanceOf(RedirectResponse::class, $response);

        $trickNull = $entityManager->getRepository(Trick::class)
                                     ->loadOneTrickWithCategoryAndAuthor('mute');

        self::assertNull($trickNull);
    }
}
