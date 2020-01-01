<?php

namespace App\Tests\Fixtures\Traits;

use App\Domain\Model\Interfaces\PictureInterface;
use App\Domain\Model\Picture;
use App\Domain\Model\Video;

trait PictureAndVideoFixtures
{
    /**
     * @var PictureInterface
     */
    protected $pictureNoHead;
    /**
     * @var PictureInterface
     */
    protected $pictureHead;
    /**
     * @var PictureInterface
     */
    protected $pictureProfile;

    /**
     * @var Video
     */
    protected $videoTrick;

    public function constructPicturesAndVideos()
    {
        $this->constructCategoryAndTrick();

        $this->pictureHead = new Picture(
            'HeadPicture123456789',
            'Description de la photo d\'en-tête.',
            'jpeg',
            true,
            $this->mute
        );

        $this->pictureNoHead = new Picture(
            'NoHeadPicture123456789',
            'Description de la photo de trick.',
            'jpeg',
            false,
            $this->mute
        );

        $this->pictureProfile = new Picture(
            'ProfilePicture123456789',
            'Description de la photo de profil.',
            'jpeg',
            false
        );

        $this->johnDoe->updateProfile(
            $this->johnDoe->getFirstName(),
            $this->johnDoe->getLastName(),
            $this->johnDoe->getMail(),
            $this->pictureProfile
        );

        $this->videoTrick = new Video('jXM-2FvU0f0', $this->mute);
    }

    use TrickAndCategoryFixtures;
}
