<?php

namespace App\Tests\Application\FormFactory;

use App\Application\FormFactory\CommentModificationFormFactory;
use App\Domain\DTO\Interfaces\Comment\CommentDTOInterface;
use App\Domain\Model\Category;
use App\Domain\Model\Comment;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class CommentModificationFormFactoryTest extends KernelTestCase
{
    private $comment;
    private $commentModificationFormFactory;

    protected function setUp()
    {

        $kernel = self::bootKernel();

        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropSchema($entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($entityManager->getMetadataFactory()->getAllMetadata());

        $user = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john@doe.fr',
            '12345678'
        );
        $entityManager->persist($user);

        $category = new Category(
            'grab',
            'Grab'
        );
        $entityManager->persist($category);

        $trick = new Trick(
            'mute',
            'Mute',
            'Description de la figure',
            $category,
            $user
        );
        $entityManager->persist($trick);

        $this->comment = new Comment('Commentaire simulé', $trick, $user);
        $entityManager->persist($this->comment);
        $entityManager->flush();

        $formFactory = $kernel->getContainer()->get('form.factory');

        $this->commentModificationFormFactory = new CommentModificationFormFactory($formFactory);
    }

    public function testIfTheFormIsPrefilledIfRequestWithGetDatasAndCommentIsInTheSession()
    {
        $request = new Request();
        $request->query->set('action', 'modifier');
        $request->query->set('id', $this->comment->getId()->toString());

        $session = new Session(new MockArraySessionStorage());
        $session->set('comment', $this->comment);
        $request->setSession($session);

        $form = $this->commentModificationFormFactory->create($request);

        $formDatas = $form->getData();
        self::assertInstanceOf(CommentDTOInterface::class, $formDatas);
    }

    public function testIfTheFormIsNotPrefilledIfRequestWithOneGetDatasMissingAndCommentIsInTheSession()
    {
        $request = new Request();
        $request->query->set('id', $this->comment->getId()->toString());

        $session = new Session(new MockArraySessionStorage());
        $session->set('comment', $this->comment);
        $request->setSession($session);

        $form = $this->commentModificationFormFactory->create($request);

        $formDatas = $form->getData();
        self::assertNull($formDatas);

        $request = new Request();
        $request->query->set('action', 'modifier');

        $session = new Session(new MockArraySessionStorage());
        $session->set('comment', $this->comment);
        $request->setSession($session);

        $form = $this->commentModificationFormFactory->create($request);

        $formDatas = $form->getData();
        self::assertNull($formDatas);
    }

    public function testIfTheFormIsNotPrefilledIfRequestWithGetDatasAndCommentIsNotInTheSession()
    {
        $request = new Request();
        $request->query->set('id', $this->comment->getId()->toString());
        $request->query->set('action', 'modifier');

        $session = new Session(new MockArraySessionStorage());
        $request->setSession($session);

        $form = $this->commentModificationFormFactory->create($request);

        $formDatas = $form->getData();
        self::assertNull($formDatas);
    }

    public function testIfTheFormIsNotPrefilledIfRequestWithWrongGetDatasAndCommentIsInTheSession()
    {
        $request = new Request();
        $request->query->set('id', $this->comment->getId()->toString());
        $request->query->set('action', 'badAction');

        $session = new Session(new MockArraySessionStorage());
        $request->setSession($session);

        $form = $this->commentModificationFormFactory->create($request);

        $formDatas = $form->getData();
        self::assertNull($formDatas);
    }
}
