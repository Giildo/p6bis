<?php

namespace App\Tests\Domain\Repository;

use App\Domain\Model\Category;
use App\Domain\Model\Comment;
use App\Domain\Model\Interfaces\CommentInterface;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use App\Domain\Repository\CommentRepository;
use App\Domain\Repository\TrickRepository;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CommentRepositoryTest extends KernelTestCase
{
    public function testTheCommentSaving()
    {
        $kernel = self::bootKernel();

        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        /** @var CommentRepository $repository */
        $repository = $entityManager->getRepository(Comment::class);

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
        $entityManager->flush();

        $comment = new Comment(
            'Commentaire simulé',
            $trick,
            $user
        );

        $repository->saveComment($comment);

        $comment = $repository->loadOneCommentWithHerId($comment->getId());

        self::assertInstanceOf(CommentInterface::class, $comment);
    }
}
