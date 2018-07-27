<?php

namespace App\Tests\Domain\Repository;

use App\Application\Helpers\SluggerHelper;
use App\Domain\Model\Category;
use App\Domain\Model\Interfaces\CategoryInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Picture;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use App\Domain\Model\Video;
use Doctrine\ORM\Tools\SchemaTool;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class TrickRepositoryTest extends KernelTestCase
{
    private $repository;

    public function setUp()
    {
        $kernel = static::bootKernel();
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->repository = $entityManager->getRepository(Trick::class);

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropSchema($entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($entityManager->getMetadataFactory()->getAllMetadata());

        $faker = Factory::create('fr_FR');

        $user = new User(
            $faker->userName,
            $faker->firstName,
            $faker->lastName,
            $faker->email,
            $faker->password
        );

        $slugger = new SluggerHelper();

        $category = new Category('Grabs', $slugger);

        $trick = new Trick(
            'Mute',
            'Figure de snowboard',
            $slugger,
            $category,
            $user
        );

        $trick->publish();

        $picture = new Picture(
            'mute001',
            'Photo de la figure Mute',
            'jpg',
            false,
            $trick
        );

        $video = new Video(
            'Ehhdj55mdS',
            $trick
        );

        $entityManager->persist($trick);
        $entityManager->persist($picture);
        $entityManager->persist($video);

        for ($i = 0 ; $i < 10 ; $i++) {
            $trick = new Trick(
                $faker->unique()->word,
                $faker->text,
                $slugger,
                $category,
                $user
            );

            $trick->publish();

            $entityManager->persist($trick);
        }

        $entityManager->flush();
    }

    public function testLoadingOfTheTricks()
    {
        $tricks = $this->repository->loadAllTricksWithAuthorCategoryAndHeadPicture();

        self::assertInstanceOf(TrickInterface::class, $tricks[0]);
        self::assertInstanceOf(UserInterface::class, $tricks[0]->getAuthor());
        self::assertInstanceOf(CategoryInterface::class, $tricks[0]->getCategory());
    }

    public function testLoadingOfOneTrick()
    {
        $trick = $this->repository->loadOneTrickWithCategoryAndAuthor('mute');

        self::assertInstanceOf(TrickInterface::class, $trick);
    }
}
