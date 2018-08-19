<?php

namespace App\Tests\Domain\Repository;

use App\Domain\Model\Category;
use App\Domain\Model\Interfaces\PictureInterface;
use App\Domain\Model\Picture;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use App\Domain\Repository\PictureRepository;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PictureRepositoryTest extends KernelTestCase
{
    public function testTheLoadingOfOnePictureWithHisNameAndThePictureDeletion()
    {
        $kernel = self::bootKernel();

        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        /** @var PictureRepository $repository */
        $repository = $entityManager->getRepository(Picture::class);

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

        $picture = new Picture(
            'mute20180812125312_1',
            'Description de la photo',
            'jpeg',
            false,
            $trick
        );

        $entityManager->persist($picture);
        $entityManager->flush();

        /** @var PictureInterface $pictureLoaded */
        $pictureLoaded = $repository->loadOnePictureWithName('mute20180812125312_1');

        self::assertInstanceOf(PictureInterface::class, $pictureLoaded);
        self::assertEquals('Description de la photo', $pictureLoaded->getDescription());
        self::assertFalse($pictureLoaded->isHeadPicture());

        $repository->deletePicture($picture);

        $newPictureLoaded = $repository->loadOnePictureWithName('mute20180812125312_1');

        self::assertNull($newPictureLoaded);
    }
}
