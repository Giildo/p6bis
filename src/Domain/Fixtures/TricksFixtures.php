<?php

namespace App\Domain\Fixtures;

use App\Application\Helpers\SluggerHelper;
use App\Domain\Model\Category;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class TricksFixtures extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $slugger = new SluggerHelper();

        $user = new User(
            $faker->userName,
            $faker->firstName,
            $faker->lastName,
            $faker->email,
            $faker->password
        );

        $category = new Category('Rotations', $slugger);

        for ($i = 0 ; $i < 20 ; $i++) {
            $trick = new Trick(
                $faker->unique()->word,
                $faker->text,
                $slugger,
                $category,
                $user
            );

            $manager->persist($trick);
        }

        $manager->flush();
    }
}
