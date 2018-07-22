<?php

namespace App\Tests\Domain\Repository;

use App\Application\Helpers\SluggerHelper;
use App\Domain\Model\Category;
use App\Domain\Model\Interfaces\VideoInterface;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use App\Domain\Model\Video;
use Doctrine\ORM\Tools\SchemaTool;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class VideoRepositoryTest extends KernelTestCase
{
    private $repository;

    public function setUp()
    {
        $kernel = static::bootKernel();
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->repository = $entityManager->getRepository(Video::class);

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

        $video1 = new Video(
            '6z6KBAbM0MY',
            $trick
        );

        $video2 = new Video(
            'bfcxdox293s',
            $trick
        );

        $trick->publish();

        $entityManager->persist($video1);
        $entityManager->persist($video2);

        $entityManager->flush();
    }

    public function testTheLoadingOfPictures()
    {
        $pictures = $this->repository->loadVideoByTrick('mute');

        self::assertInstanceOf(VideoInterface::class, $pictures[0]);
        self::assertInstanceOf(VideoInterface::class, $pictures[1]);
    }
}
