<?php

namespace App\Tests\Domain\Builders;

use App\Domain\Builders\CommentBuilder;
use App\Domain\DTO\Comment\AddCommentDTO;
use App\Domain\Model\Category;
use App\Domain\Model\Interfaces\CommentInterface;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentBuilderTest extends TestCase
{
    public function testTheCommentBuildingWithDTO()
    {
        $user = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john@doe.fr',
            '12345678'
        );

        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($user);
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->method('getToken')->willReturn($token);

        $dto = new AddCommentDTO('Commentaire simulé.');

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

        $builder = new CommentBuilder($tokenStorage);

        $comment = $builder->build($dto, $trick)
                           ->getComment();

        self::assertInstanceOf(CommentInterface::class, $comment);
        self::assertEquals('Commentaire simulé.', $comment->getComment());
        self::assertInstanceOf(UserInterface::class, $comment->getAuthor());
    }
}
