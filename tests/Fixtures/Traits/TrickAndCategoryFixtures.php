<?php

namespace App\Tests\Fixtures\Traits;

use App\Domain\Model\Category;
use App\Domain\Model\Interfaces\CategoryInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Trick;

trait TrickAndCategoryFixtures
{
    /**
     * @var TrickInterface
     */
    protected $mute;
    /**
     * @var TrickInterface
     */
    protected $r180;

    /**
     * @var CategoryInterface
     */
    protected $grab;
    /**
     * @var CategoryInterface
     */
    protected $rotations;

    /**
     * @var TrickInterface[]
     */
    protected $tricksList;

    public function constructCategoryAndTrick()
    {
        $this->constructUsers();

        $this->grab = new Category(
            'grab',
            'Grab'
        );

        $this->rotations = new Category(
            'rotations',
            'Rotations'
        );

        $this->mute = new Trick(
            'mute',
            'Mute',
            'Description de la figure',
            $this->grab,
            $this->johnDoe,
            true
        );

        $this->r180 = new Trick(
            '180',
            '180',
            'Description de la figure',
            $this->rotations,
            $this->janeDoe,
            true
        );

        for ($i = 1 ; $i <= 15 ; $i++) {
            $this->tricksList[] = new Trick(
                'trick slug_' . $i,
                'Trick name_' .$i,
                'Description de la trick N°' . $i,
                $this->grab,
                $this->johnDoe,
                true
            );
        }
    }

    use UsersFixtures;
}
