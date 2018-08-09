<?php

namespace App\Application\Helpers\Interfaces;

use App\Domain\Model\Picture;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface PictureSaveHelperInterface
{
    /**
     * @param array $picturesAndFiles
     * @return void
     */
    public function save(array $picturesAndFiles): void;
}