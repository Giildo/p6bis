<?php

namespace App\Application\Helpers;

use App\Application\Helpers\Interfaces\PictureSaveHelperInterface;

class PictureSaveHelper implements PictureSaveHelperInterface
{
    /**
     * {@inheritdoc}
     */
    public function save(array $picturesAndFiles): void {
        foreach ($picturesAndFiles as $pictureAndFile) {
            $picture = $pictureAndFile[0];
            $file = $pictureAndFile[1];

            $fileName = $picture->getName() . '.' . $file->guessExtension();

            $file->move(
                __DIR__ . '/../../../public/pic/tricks/',
                $fileName
            );
        }
    }
}
