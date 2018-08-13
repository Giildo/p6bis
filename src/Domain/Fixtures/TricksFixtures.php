<?php

namespace App\Domain\Fixtures;

use App\Application\Helpers\SluggerHelper;
use App\Domain\Model\Category;
use App\Domain\Model\Picture;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use App\Domain\Model\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TricksFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoderFactory;

    /**
     * TricksFixtures constructor.
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $slugger = new SluggerHelper();

        $encoder = $this->encoderFactory->getEncoder(User::class);
        $password = $encoder->encodePassword('12345678', '');

        $user1 = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john@doe.com',
            $password
        );

        $user2 = new User(
            'JaneDoe',
            'Jane',
            'Doe',
            'jane@doe.com',
            $password
        );

        $user2->changeRole(['ROLE_ADMIN']);

        $category = new Category($slugger->slugify('Rotations'), 'Rotations');

        for ($i = 0 ; $i < 15 ; $i++) {
            $name = $faker->unique()->word;
            $trick = new Trick(
                $slugger->slugify($name),
                $name,
                $faker->text,
                $category,
                $user1
            );

            $trick->publish();

            $manager->persist($trick);
        }

        for ($i = 0 ; $i < 5 ; $i++) {
        	$name = $faker->unique()->word;
            $trick = new Trick(
                $slugger->slugify($name),
                $name,
                $faker->text,
                $category,
                $user2
            );

            $trick->publish();

            $manager->persist($trick);
        }

        $trick = new Trick(
        	$slugger->slugify('Mute'),
	        'Mute',
	        'Description de la figure Mute',
	        $category,
	        $user1
        );

        $picture = new Picture(
        	"{$trick->getSlug()}0012018",
	        'Description de l\'image',
	        'jpeg',
	        false,
	        $trick
        );

        $manager->persist($picture);

	    $picture = new Picture(
		    "{$trick->getSlug()}0022018",
		    'Description de l\'image',
		    'jpeg',
		    false,
		    $trick
	    );

	    $manager->persist($picture);

	    $video = new Video('3TAUnYZpMbA', $trick);

	    $manager->persist($video);

        $manager->flush();
    }
}
