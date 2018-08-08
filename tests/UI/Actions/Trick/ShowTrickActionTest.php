<?php

namespace App\Tests\UI\Actions\Trick;

use App\Application\Helpers\SluggerHelper;
use App\Domain\Model\Category;
use App\Domain\Model\Trick;
use App\Domain\Model\User;
use App\UI\Actions\Trick\ShowTrickAction;
use App\UI\Presenters\Interfaces\Trick\ShowTrickPresenterInterface;
use App\UI\Responders\Trick\ShowTrickResponder;
use Doctrine\ORM\Tools\SchemaTool;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ShowTrickActionTest extends KernelTestCase
{
    private $action;

    public function setUp()
    {
        $kernel = self::bootKernel();

        $presenter = $this->createMock(ShowTrickPresenterInterface::class);
        $presenter->method('showTrickPresentation')->willReturn('Vue de la page');

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('url');

        $responder = new ShowTrickResponder($presenter, $urlGenerator);

        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropSchema($entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($entityManager->getMetadataFactory()->getAllMetadata());

        $faker = Factory::create('fr_FR');

        $user = new User(
            $faker->userName,
            $faker->firstName,
            $faker->lastName,
            $faker->email,
            $faker->password
        );

        $slugger = new SluggerHelper();

        $category = new Category(
            $slugger->slugify('Grabs'),
            'Grabs'
        );

        $trick = new Trick(
            $slugger->slugify('Mute'),
            'Mute',
            'Figure de snowboard',
            $category,
            $user
        );

        $trick->publish();

        $entityManager->persist($trick);

        for ($i = 0 ; $i < 10 ; $i++) {
            $name = $faker->unique()->word;
            $trick = new Trick(
                $slugger->slugify($name),
                $name,
                $faker->text,
                $category,
                $user
            );

            $trick->publish();

            $entityManager->persist($trick);
        }

        $entityManager->flush();

        $this->action = new ShowTrickAction($entityManager, $responder);
    }

    public function testConstructor()
    {
        self::assertInstanceOf(ShowTrickAction::class, $this->action);
    }

    public function testRedirectResponseIfSlugForEntityIsWrong()
    {
        self::assertInstanceOf(RedirectResponse::class, $this->action->showTrick('badSlug'));
    }

    public function testResponseIfSlugForEntityIsGood()
    {
        $response = $this->action->showTrick('mute');

        self::assertInstanceOf(Response::class, $response);
        self::assertNotInstanceOf(RedirectResponse::class, $response);
    }
}
