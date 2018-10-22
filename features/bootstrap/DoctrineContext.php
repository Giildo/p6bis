<?php

use App\Application\Helpers\SluggerHelper;
use App\Domain\Model\Category;
use App\Domain\Model\Comment;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Interfaces\UserInterface;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Nelmio\Alice\Loader\NativeLoader;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DoctrineContext extends MinkContext implements Context
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var UserInterface
     */
    private $user1;

    /**
     * @var UserInterface
     */
    private $user2;

    /**
     * @var TrickInterface
     */
    private $trick1;

    /**
     * DoctrineContext constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    )
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @BeforeScenario
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    public function initDatabase()
    {
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
    }

    /**
     * @Given I load following file :path
     */
    public function iLoadFollowingFile($path)
    {
        $loader = new NativeLoader();
        $objects = $loader->loadFile(__DIR__ . '/../fixtures/' . $path);

        foreach ($objects->getObjects() as $object) {
            if ($object instanceof User) {
                $object->changePassword($this->passwordEncoder->encodePassword($object, $object->getPassword()));
            }

            $this->entityManager->persist($object);
        }

        $this->entityManager->flush();
    }

    /**
     * @Given I am logged with username :username and with password :password
     */
    public function iAmLoggedWithUsernameAndWithPassword($username, $password)
    {
        $this->visit('/connexion');
        $this->fillField('user_connection_username', $username);
        $this->fillField('user_connection_password', $password);
        $this->pressButton('Se connecter');
    }

    /**
     * @Given /^I am on the second step of recovery password processus$/
     */
    public function iAmOnTheSecondStepOfRecoveryPasswordProcessus()
    {
        $this->iLoadASpecificUser();

        $this->visit('/recuperation');
        $this->fillField('password_recovery_for_username_username', 'JohnDoe');
        $this->pressButton('Valider');
    }

    /**
     * @Given I load following file :path with recovery token
     */
    public function iLoadFollowingFileWithRecoveryToken($path)
    {
        $this->iLoadFollowingFile($path);
    }

    /**
     * @Given I am on recuperation uri with bad token and with queries datas
     */
    public function iAmOnWithBadTokenAndWithPrefix()
    {
        $this->visit("/recuperation/mot-de-passe?ut=token");
    }

    /**
     * @Given I am on recuperation uri with token and with queries datas
     */
    public function iAmOnWithTokenAndWithPrefix()
    {
        $token = $this->entityManager->getRepository(User::class)
                                     ->loadUserByUsername('JohnDoe')
                                     ->getToken();

        $this->visit("/recuperation/mot-de-passe?ut={$token}");
    }

    /**
     * @Given I load a specific user
     */
    public function iLoadASpecificUser()
    {
        $this->user1 = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john@doe.com'
        );

        $this->user2 = new User(
            'JaneDoe',
            'Jane',
            'Doe',
            'jane@doe.com'
        );

        $this->user2->changeRole(['ROLE_ADMIN']);

        $this->user1->changePassword(
            $this->passwordEncoder->encodePassword(
                $this->user1,
                '12345678'
            )
        );
        $this->user2->changePassword($this->passwordEncoder->encodePassword(
            $this->user2,
            '12345678'
        )
        );

        $this->entityManager->persist($this->user1);
        $this->entityManager->persist($this->user2);

        $this->entityManager->flush();
    }

    /**
     * @Given I load the tricks with category and user
     */
    public function iLoadTheTricksWithCategoryAndUser()
    {
        $this->iLoadASpecificUser();

        $slugger = new SluggerHelper();

        $grabs = new Category($slugger->slugify('Grabs'), 'Grabs');
        $rotations = new Category($slugger->slugify('Rotations'), 'Rotations');
        $flips = new Category($slugger->slugify('Flips'), 'Flips');

        $mute = new Trick(
            $slugger->slugify('Mute'),
            'Mute',
            'Le snowboarder saisit la carre frontside de la planche entre les deux pieds avec la main avant.',
            $grabs,
            $this->user1,
            true
        );
        $this->entityManager->persist($mute);
        $this->trick1 = $mute;

        $melancholie = new Trick(
            $slugger->slugify('Melancholie'),
            'Melancholie',
            'Le snowboarder saisit la carre backside de la planche, entre les deux pieds, avec la main avant.',
            $grabs,
            $this->user1,
            true
        );
        $this->entityManager->persist($melancholie);

        $indy = new Trick(
            $slugger->slugify('Indy'),
            'Indy',
            'Le snowboarder saisit la carre frontside de la planche, entre les deux pieds, avec la main arrière.',
            $grabs,
            $this->user1,
            true
        );
        $this->entityManager->persist($indy);

        $r180 = new Trick(
            $slugger->slugify('180'),
            '180',
            'Le snowboarder fait un demi tour et change de pied d\'appuie.',
            $rotations,
            $this->user2,
            true
        );
        $this->entityManager->persist($r180);

        $stalefish = new Trick(
            $slugger->slugify('Stalefish'),
            'Stalefish',
            'Le snowboarder saisit la carre backside de la planche entre les deux pieds avec la main arrière.',
            $grabs,
            $this->user2,
            true
        );
        $this->entityManager->persist($stalefish);

        $tail = new Trick(
            $slugger->slugify('Tail'),
            'Tail',
            'Le snowboarder saisit la partie arrière de la planche, avec la main arrière.',
            $grabs,
            $this->user1,
            true
        );
        $this->entityManager->persist($tail);

        $nose = new Trick(
            $slugger->slugify('Nose'),
            'Nose',
            'Le snowboarder saisit la partie avant de la planche, avec la main avant.',
            $grabs,
            $this->user1,
            true
        );
        $this->entityManager->persist($nose);

        $r360 = new Trick(
            $slugger->slugify('360'),
            '360',
            'Le snowboarder fait un complet et ne change donc pas de pied d\'appuie.',
            $rotations,
            $this->user2,
            true
        );
        $this->entityManager->persist($r360);

        $japan = new Trick(
            $slugger->slugify('Japan'),
            'Japan',
            'Le snowboarder saisit l\'avant de la planche, avec la main avant, du côté de la carre frontside.',
            $grabs,
            $this->user1,
            true
        );
        $this->entityManager->persist($japan);

        $seatBelt = new Trick(
            $slugger->slugify('Seat belt'),
            'Seat belt',
            'Le snowboarder saisit la carre frontside à l\'arrière avec la main avant.',
            $grabs,
            $this->user2,
            true
        );
        $this->entityManager->persist($seatBelt);

        $truckDriver = new Trick(
            $slugger->slugify('Truck driver'),
            'Truck driver',
            'Le snowboarder saisit la carre avant et carre arrière avec chaque main (comme pour tenir un volant de voiture).',
            $grabs,
            $this->user1,
            false
        );
        $this->entityManager->persist($truckDriver);

        $truckDriver = new Trick(
            $slugger->slugify('Font flips'),
            'Font flips',
            'Le snowboarder fait des rotations vers l\'avant. Cette technique peut être associée à des figures de type grab.',
            $flips,
            $this->user1,
            true
        );
        $this->entityManager->persist($truckDriver);

        $this->entityManager->flush();
    }

    /**
     * @Given /^I load the tricks with category and user with simulated comment$/
     */
    public function iLoadTheTricksWithCategoryAndUserWithSimulatedComment()
    {
        $this->iLoadTheTricksWithCategoryAndUser();

        $comment = new Comment(
            'Commentaire simulé.',
            $this->trick1,
            $this->user1
        );

        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }


    /**
     * @Then I should see :text :number times in element :selector
     */
    public function iShouldSeeTimes($text, $number, $selector)
    {
        $this->assertSession()->elementsCount(
            'named',
            [$selector, $text],
            $number
        );
    }

    /**
     * @Given I am on :uri with get datas information
     */
    public function iAmOnWithGetDatasInformation($uri)
    {
        $comment = $this->entityManager->getRepository(Comment::class)
                                       ->loadAllCommentsOfATrick('mute');

        $this->visit("{$uri}?action=modifier&id={$comment[0]->getId()}");
    }

    /**
     * @Given I am on the deletion page
     */
    public function iAmOnTheDeletionPage()
    {
        $comment = $this->entityManager->getRepository(Comment::class)
                                       ->loadAllCommentsOfATrick('mute');

        $this->visit("/trick/mute/suppression-commentaire?action=suppression&id={$comment[0]->getId()}");
    }

}
