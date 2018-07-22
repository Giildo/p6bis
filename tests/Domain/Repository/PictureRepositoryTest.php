<?php

namespace App\Tests\Domain\Repository;

use App\Application\Helpers\SluggerHelper;
use App\Domain\Model\Category;
use App\Domain\Model\Interfaces\PictureInterface;
use App\Domain\Model\Picture;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use Doctrine\ORM\Tools\SchemaTool;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PictureRepositoryTest extends KernelTestCase
{
    private $repository;

    public function setUp()
    {
        $kernel = static::bootKernel();
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->repository = $entityManager->getRepository(Picture::class);

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

        $picture1 = new Picture(
            'mute001',
            'Photo de la figure Mute',
            'jpg',
            true,
            $trick
        );

        $picture2 = new Picture(
            'mute002',
            'Photo de la figure Mute',
            'jpg',
            false,
            $trick
        );

        $trick->publish();

        $entityManager->persist($picture1);
        $entityManager->persist($picture2);

        $entityManager->flush();
    }

    public function testTheLoadingOfPictures()
    {
        $pictures = $this->repository->loadPictureByTrick('mute');

        self::assertInstanceOf(PictureInterface::class, $pictures[0]);
        self::assertInstanceOf(PictureInterface::class, $pictures[1]);
    }
}
