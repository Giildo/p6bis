<?php

namespace App\Tests\Domain\Repository;

use App\Domain\Model\Category;
use App\Domain\Model\Interfaces\VideoInterface;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use App\Domain\Model\Video;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class VideoRepositoryTest extends KernelTestCase
{
    public function testTheLoadingOfOneVideoWithHisName()
    {
        $kernel = self::bootKernel();

        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $repository = $entityManager->getRepository(Video::class);

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropSchema($entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($entityManager->getMetadataFactory()->getAllMetadata());

        $category = new Category('grab', 'Grab');
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
            'Description de la figure',
            $category,
            $author
        );

        $video = new Video(
            'B3Lhid5_HJc',
            $trick
        );

        $entityManager->persist($video);
        $entityManager->flush();

        /** @var VideoInterface $videoLoaded */
        $videoLoaded = $repository->loadOneVideoWithName('B3Lhid5_HJc');

        self::assertInstanceOf(VideoInterface::class, $videoLoaded);
        self::assertEquals('B3Lhid5_HJc', $videoLoaded->getName());
    }
}
