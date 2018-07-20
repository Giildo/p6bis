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
        $this->iLoadFollowingFile('/user/01.specific_user.yml');

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
            $faker->text,
            $slugger,
            $category,
            $user
        );

        $trick->publish();

        $this->entityManager->persist($trick);

        for ($i = 0 ; $i < 8 ; $i++) {
            $trick = new Trick(
                $faker->unique()->word,
                $faker->text,
                $slugger,
                $category,
                $user
            );

            $trick->publish();

            $this->entityManager->persist($trick);
        }

        $trick = new Trick(
            'Truck',
            $faker->text,
            $slugger,
            $category,
            $user
        );

        $trick->publish();

        $this->entityManager->persist($trick);

        $this->entityManager->flush();
    }
}
