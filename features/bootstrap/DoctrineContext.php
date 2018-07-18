<?php

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
        $this->fillField('user_connection_username', 'JohnDoe');
        $this->fillField('user_connection_password', '12345678');
        $this->pressButton('Valider');
    }
}
