<?php

namespace App\Tests\Application\FormFactory;

use App\Application\FormFactory\CommentModificationFormFactory;
use App\Application\FormFactory\Interfaces\CommentModificationFormFactoryInterface;
use App\Domain\DTO\Interfaces\Comment\CommentDTOInterface;
use App\Tests\Fixtures\Traits\CommentFixtures;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class CommentModificationFormFactoryTest extends KernelTestCase
{
    /**
     * @var CommentModificationFormFactoryInterface
     */
    private $commentModificationFormFactory;

    /**
     * @throws ORMException
     * @throws ToolsException
     */
    protected function setUp()
    {
        $this->constructComments();

        $kernel = self::bootKernel();

        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropSchema($entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($entityManager->getMetadataFactory()->getAllMetadata());

        $entityManager->persist($this->johnDoe);
        $entityManager->persist($this->grab);
        $entityManager->persist($this->mute);
        $entityManager->persist($this->comment1);
        $entityManager->flush();

        $formFactory = $kernel->getContainer()->get('form.factory');

        $this->commentModificationFormFactory = new CommentModificationFormFactory($formFactory);
    }

    use CommentFixtures;

    public function testIfTheFormIsPrefilledIfRequestWithGetDatasAndCommentIsInTheSession()
    {
        $request = new Request();
        $request->query->set('action', 'modifier');
        $request->query->set('id', $this->comment1->getId()->toString());

        $session = new Session(new MockArraySessionStorage());
        $session->set('comment', $this->comment1);
        $request->setSession($session);

        $form = $this->commentModificationFormFactory->create($request);

        $formDatas = $form->getData();
        self::assertInstanceOf(CommentDTOInterface::class, $formDatas);
    }

    public function testIfTheFormIsNotPrefilledIfRequestWithOneGetDatasMissingAndCommentIsInTheSession()
    {
        $request = new Request();
        $request->query->set('id', $this->comment1->getId()->toString());

        $session = new Session(new MockArraySessionStorage());
        $session->set('comment', $this->comment1);
        $request->setSession($session);

        $form = $this->commentModificationFormFactory->create($request);

        $formDatas = $form->getData();
        self::assertNull($formDatas);

        $request = new Request();
        $request->query->set('action', 'modifier');

        $session = new Session(new MockArraySessionStorage());
        $session->set('comment', $this->comment1);
        $request->setSession($session);

        $form = $this->commentModificationFormFactory->create($request);

        $formDatas = $form->getData();
        self::assertNull($formDatas);
    }

    public function testIfTheFormIsNotPrefilledIfRequestWithGetDatasAndCommentIsNotInTheSession()
    {
        $request = new Request();
        $request->query->set('id', $this->comment1->getId()->toString());
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
        $request->query->set('id', $this->comment1->getId()->toString());
        $request->query->set('action', 'badAction');

        $session = new Session(new MockArraySessionStorage());
        $request->setSession($session);

        $form = $this->commentModificationFormFactory->create($request);

        $formDatas = $form->getData();
        self::assertNull($formDatas);
    }
}
