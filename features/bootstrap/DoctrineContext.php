<?php

use App\Application\Helpers\SluggerHelper;
use App\Domain\Model\Category;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Faker\Factory;
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
     * DoctrineContext constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
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
        $this->pressButton('Valider');
    }

    /**
     * @Given I load following file :path with recovery token
     */
    public function iLoadFollowingFileWithRecoveryToken($path)
    {
        $this->iLoadFollowingFile($path);

        $this->visit('/recuperation');
        $this->fillField('password_recovery_for_username_username', 'JohnDoe');
        $this->pressButton('Valider');
    }

    /**
     * @Given I am on :uri with bad token and with prefix :prefix
     */
    public function iAmOnWithBadTokenAndWithPrefix($uri, $prefix)
    {
        $this->visit("{$uri}?{$prefix}=token");
    }

    /**
     * @Given I am on :uri with token and with prefix :prefix
     */
    public function iAmOnWithTokenAndWithPrefix($uri, $prefix)
    {
        $repository = $this->entityManager->getRepository(User::class);
        $user = $repository->loadUserByUsername('JohnDoe');
        $token = $user->getToken();

        $this->visit("{$uri}?{$prefix}={$token}");
    }

    /**
     * @Given I load the tricks with category and user
     */
    public function iLoadTheTricksWithCategoryAndUser()
    {
        $faker = Factory::create('fr_FR');

        $user1 = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john@doe.com',
            '12345678'
        );

        $user2 = new User(
            'JaneDoe',
            'Jane',
            'Doe',
            'jane@doe.com',
            '12345678'
        );

        $user2->changeRole(['ROLE_ADMIN']);

        $user1->changePassword($this->passwordEncoder->encodePassword($user1, $user1->getPassword()));
        $user2->changePassword($this->passwordEncoder->encodePassword($user2, $user2->getPassword()));

        $slugger = new SluggerHelper();

        $category = new Category($slugger->slugify('Grabs'), 'Grabs');
        $category = new Category($slugger->slugify('Rotations'), 'Rotations');

        $trick = new Trick(
            $slugger->slugify('Mute'),
            'Mute',
            $faker->text,
            $category,
            $user1
        );

        $trick->publish();

        $this->entityManager->persist($trick);

        for ($i = 0 ; $i < 8 ; $i++) {
            $name = $faker->unique()->word;
            $trick = new Trick(
                $slugger->slugify($name),
                $name,
                $faker->text,
                $category,
                $user2
            );

            $trick->publish();

            $this->entityManager->persist($trick);
        }

        $trick = new Trick(
            $slugger->slugify('Truck'),
            'Truck',
            $faker->text,
            $category,
            $user1
        );

        $trick->publish();

        $this->entityManager->persist($trick);

        $this->entityManager->flush();
    }


    /**
     * @Then I should see :text :number times
     */
    public function iShouldSeeTimes($text, $number)
    {
        $this->assertSession()->elementsCount(
            'named',
            ['button', $text],
            $number
        );
    }

}
