<?php

namespace App\Tests\Domain\Model;

use App\Domain\DTO\Comment\CommentModificationDTO;
use App\Domain\Model\Category;
use App\Domain\Model\Comment;
use App\Domain\Model\Interfaces\CommentInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use App\Domain\Repository\TrickRepository;
use DateTime;
use Doctrine\ORM\Tools\SchemaTool;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentTest extends KernelTestCase
{
    private $comment;

    protected function setUp()
    {
        $kernel = self::bootKernel();

        $user = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john@doe.fr',
            '12345678'
        );

        $category = new Category(
            'grab',
            'Grab'
        );

        $trick = new Trick(
            'mute',
            'Mute',
            'Description de la figure',
            $category,
            $user
        );

        $this->comment = new Comment(
            'Commentaire simulé.',
            $trick,
            $user
        );

        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropSchema($entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($entityManager->getMetadataFactory()->getAllMetadata());

        $entityManager->persist($user);
        $entityManager->persist($category);
        $entityManager->persist($trick);
        $entityManager->persist($this->comment);
        $entityManager->flush();

        /** @var TrickRepository $repository */
        $repository = $entityManager->getRepository(Trick::class);
        $trick = $repository->loadOneTrickWithCategoryAndAuthor('mute');
        $this->comment = $trick->getComments()[0];
    }

    public function testConstructor()
    {
        self::assertInstanceOf(CommentInterface::class, $this->comment);
    }

    public function testTheReturnsValueAndType()
    {
        self::assertInstanceOf(UuidInterface::class, $this->comment->getId());
        self::assertInternalType('string', $this->comment->getComment());
        self::assertEquals('Commentaire simulé.', $this->comment->getComment());
        self::assertInstanceOf(DateTime::class, $this->comment->getCreatedAt());
        self::assertInstanceOf(TrickInterface::class, $this->comment->getTrick());
        self::assertInstanceOf(UserInterface::class, $this->comment->getAuthor());
    }

    public function testTheUpdate()
    {
        $dto = new CommentModificationDTO('Commentaire simulté et modifié.');

        $this->comment->updateComment($dto);

        self::assertInstanceOf(DateTime::class, $this->comment->getUpdatedAt());
    }
}
