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
        $slugger = new SluggerHelper();

        $encoder = $this->encoderFactory->getEncoder(User::class);
        $password = $encoder->encodePassword('12345678', '');

        $john = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john@doe.com'
        );
        $john->changePassword($password);
        $manager->persist($john);

        $jane = new User(
            'JaneDoe',
            'Jane',
            'Doe',
            'jane@doe.com'
        );
        $jane->changePassword($password);
        $jane->changeRole(['ROLE_ADMIN']);

        $manager->persist($jane);

        $grabs = new Category($slugger->slugify('Grabs'), 'Grabs');
        $manager->persist($grabs);
        $rotations = new Category($slugger->slugify('Rotations'), 'Rotations');
        $manager->persist($rotations);
        $flips = new Category($slugger->slugify('Flips'), 'Flips');
        $manager->persist($flips);
        $manager->flush();

        $mute = new Trick(
            $slugger->slugify('Mute'),
            'Mute',
            'Le snowboarder saisit la carre frontside de la planche entre les deux pieds avec la main avant.',
            $grabs,
            $john,
            true
        );
        $manager->persist($mute);

        $melancholie = new Trick(
            $slugger->slugify('Melancholie'),
            'Melancholie',
            'Le snowboarder saisit la carre backside de la planche, entre les deux pieds, avec la main avant.',
            $grabs,
            $john,
            true
        );
        $manager->persist($melancholie);

        $indy = new Trick(
            $slugger->slugify('Indy'),
            'Indy',
            'Le snowboarder saisit la carre frontside de la planche, entre les deux pieds, avec la main arrière.',
            $grabs,
            $john,
            true
        );
        $manager->persist($indy);

        $r180 = new Trick(
            $slugger->slugify('180'),
            '180',
            'Le snowboarder fait un demi tour et change de pied d\'appuie.',
            $rotations,
            $jane,
            true
        );
        $manager->persist($r180);

        $stalefish = new Trick(
            $slugger->slugify('Stalefish'),
            'Stalefish',
            'Le snowboarder saisit la carre backside de la planche entre les deux pieds avec la main arrière.',
            $grabs,
            $jane,
            true
        );
        $manager->persist($stalefish);

        $tail = new Trick(
            $slugger->slugify('Tail'),
            'Tail',
            'Le snowboarder saisit la partie arrière de la planche, avec la main arrière.',
            $grabs,
            $john,
            true
        );
        $manager->persist($tail);

        $nose = new Trick(
            $slugger->slugify('Nose'),
            'Nose',
            'Le snowboarder saisit la partie avant de la planche, avec la main avant.',
            $grabs,
            $john,
            true
        );
        $manager->persist($nose);

        $r360 = new Trick(
            $slugger->slugify('360'),
            '360',
            'Le snowboarder fait un complet et ne change donc pas de pied d\'appuie.',
            $rotations,
            $jane,
            true
        );
        $manager->persist($r360);

        $japan = new Trick(
            $slugger->slugify('Japan'),
            'Japan',
            'Le snowboarder saisit l\'avant de la planche, avec la main avant, du côté de la carre frontside.',
            $grabs,
            $john,
            true
        );
        $manager->persist($japan);

        $seatBelt = new Trick(
            $slugger->slugify('Seat belt'),
            'Seat belt',
            'Le snowboarder saisit la carre frontside à l\'arrière avec la main avant.',
            $grabs,
            $jane,
            true
        );
        $manager->persist($seatBelt);

        $truckDriver = new Trick(
            $slugger->slugify('Truck driver'),
            'Truck driver',
            'Le snowboarder saisit la carre avant et carre arrière avec chaque main (comme pour tenir un volant de voiture).',
            $grabs,
            $john,
            false
        );
        $manager->persist($truckDriver);

        $truckDriver = new Trick(
            $slugger->slugify('Font flips'),
            'Font flips',
            'Le snowboarder fait des rotations vers l\'avant. Cette technique peut être associée à des figures de type grab.',
            $flips,
            $john,
            true
        );
        $manager->persist($truckDriver);
        $manager->flush();

        $v180 = new Video(
            'XyARvRQhGgk',
            $r180
        );
        $manager->persist($v180);

        $v360 = new Video(
            'Sh3qT1INT_I',
            $r360
        );
        $manager->persist($v360);

        $v360 = new Video(
            'Opg5g4zsiGY',
            $mute
        );
        $manager->persist($v360);

        $v360 = new Video(
            'CA5bURVJ5zk',
            $mute
        );
        $manager->persist($v360);

        $v360 = new Video(
            't0F1sKMUChA',
            $indy
        );
        $manager->persist($v360);
        $manager->flush();

        $indy0 = new Picture(
            'indy20180911192313_0',
            'Photo de profil de la figure Indy.',
            'jpeg',
            true,
            $indy
        );
        $manager->persist($indy0);

        $indy1 = new Picture(
            'indy20180911192313_1',
            'Photo représentant un snowboarder faisant un Indy Grab.',
            'jpeg',
            false,
            $indy
        );
        $manager->persist($indy1);

        $indy2 = new Picture(
            'indy20180911192313_2',
            'Photo représentant un snowboarder faisant un Indy Grab.',
            'jpeg',
            false,
            $indy
        );
        $manager->persist($indy2);

        $indy3 = new Picture(
            'indy20180911192313_3',
            'Photo représentant un snowboarder faisant un Indy Grab.',
            'jpeg',
            false,
            $indy
        );
        $manager->persist($indy3);

        $stalefish0 = new Picture(
            'stalefish20180911190239_0',
            'Photo de profil de la figure Stalefish.',
            'jpeg',
            true,
            $stalefish
        );
        $manager->persist($stalefish0);

        $stalefish1 = new Picture(
            'stalefish20180911190239_1',
            'Photo représentant un snowboarder faisant un Stalefish Grab.',
            'jpeg',
            false,
            $stalefish
        );
        $manager->persist($stalefish1);

        $stalefish2 = new Picture(
            'stalefish20180911190239_2',
            'Photo représentant un snowboarder faisant un Stalefish Grab.',
            'jpeg',
            false,
            $stalefish
        );
        $manager->persist($stalefish2);

        $stalefish3 = new Picture(
            'stalefish20180911190239_3',
            'Photo représentant un snowboarder faisant un Stalefish Grab.',
            'jpeg',
            false,
            $stalefish
        );
        $manager->persist($stalefish3);

        $mute = new Picture(
            'mute20180930213132_0',
            'Photo de profil de la figure Mute.',
            'jpeg',
            true,
            $mute
        );
        $manager->persist($mute);

        $manager->flush();
    }
}
