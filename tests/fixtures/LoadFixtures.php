<?php

namespace App\Tests\fixtures;

use Doctrine\ORM\EntityManagerInterface;
use Nelmio\Alice\Loader\NativeLoader;

trait LoadFixtures
{
    private function loadFixtures(string $files, EntityManagerInterface $entityManager) {
        $loader = new NativeLoader();
        $objects = $loader->loadFile($files);

        foreach ($objects->getObjects() as $object) {
            $entityManager->persist($object);
        }

        $entityManager->flush();
    }
}
