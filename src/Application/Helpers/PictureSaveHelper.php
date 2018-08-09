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
            $file = $pictureAndFile[0];
            $picture = $pictureAndFile[1];

            $fileName = $picture->getName() . '.' . $file->guessExtension();

            $file->move(
                __DIR__ . '/../../../public/pic/tricks/',
                $fileName
            );
        }
    }
}
