<?php

namespace App\Tests\Fixtures\Traits;

use App\Domain\Model\Interfaces\UserInterface;
use App\Domain\Model\User;

trait UsersFixtures
{
    /**
     * @var UserInterface
     */
    protected $johnDoe;
    /**
     * @var UserInterface
     */
    protected $janeDoe;

    public function constructUsers()
    {
        $this->johnDoe = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john@doe.fr'
        );
        $this->johnDoe->changePassword('12345678');

        $this->janeDoe = new User(
            'JaneDoe',
            'Jane',
            'Doe',
            'jane@doe.fr'
        );
        $this->janeDoe->changePassword('12345678');
        $this->janeDoe->changeRole(['ROLE_ADMIN']);
    }
}
