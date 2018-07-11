<?php

namespace App\Tests\Application\Handlers\Forms\Security;

use App\Application\Handlers\Forms\Security\UserRegistrationHandler;
use App\Application\Handlers\Interfaces\Forms\Security\UserRegistrationHandlerInterface;
use App\Domain\Builders\UserBuilder;
use App\Domain\DTO\Security\UserRegistrationDTO;
use App\Domain\Model\User;
use App\Tests\fixtures\LoadFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRegistrationHandlerTest extends KernelTestCase
{
    private $handler;

    private $form;

    private $entityManager;

    protected function setUp()
    {
        //Kernel boot
        $kernel = self::bootKernel();

        //Creation of the repository
        $this->entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $repository = $this->entityManager->getRepository(User::class);

        //Creation of the builder
        $encoder = $this->createMock(PasswordEncoderInterface::class);
        $encoder->method('encodePassword')
            ->willReturn('$2y$10$7J3Aa2d0qHShV1lZObKT/.dKbFMYCHApGJXjK.PrJ..AdFnbGugpa');

        $encoderFactory = $this->createMock(EncoderFactoryInterface::class);
        $encoderFactory->method('getEncoder')
            ->willReturn($encoder);
        $builder = new UserBuilder($encoderFactory);

        //Creation of the handler
        $this->handler = new UserRegistrationHandler($builder, $repository);

        //Création of the DTO
        $dto = new UserRegistrationDTO(
            'JohnDoe',
            'John',
            'Doe',
            'john.doe@gmail.com',
            '12345678'
        );

        //Création of the Form
        $this->form = $this->createMock(FormInterface::class);
        $this->form->method('getData')
                   ->willReturn($dto);

        //Création of the database && loading fixtures
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());

        $this->loadFixtures(__DIR__ . '/../../../../fixtures/user_registration/00.load.yml', $this->entityManager);
    }

    use LoadFixtures;

    public function testConstructor()
    {
        self::assertInstanceOf(
            UserRegistrationHandlerInterface::class,
            $this->handler
        );
    }

    public function testReturnWhenFormIsntSubmitted()
    {
        $this->form->method('isSubmitted')->willReturn(false);
        $this->form->method('isValid')->willReturn(false);

        self::assertFalse($this->handler->handle($this->form));
    }

    public function testReturnWhenFormIsntValid()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(false);

        self::assertFalse($this->handler->handle($this->form));
    }

    public function testReturnWhenFormIsSubmittedAndIsValid()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        self::assertTrue($this->handler->handle($this->form));
    }

    public function testTheBackupOfUserInDatabase()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        $this->handler->handle($this->form);

        $repository = $this->entityManager->getRepository(User::class);
        $users = $repository->findAll();
        $userLoaded = array_pop($users);

        self::assertInstanceOf(UserInterface::class, $userLoaded);
        self::assertEquals('JohnDoe', $userLoaded->getUsername());
    }
}
