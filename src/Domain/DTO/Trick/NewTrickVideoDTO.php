<?php

namespace App\Domain\DTO\Trick;

use App\Domain\DTO\Interfaces\Trick\NewTrickVideoDTOInterface;
use Symfony\Component\Validator\Constraints as Assert;

class NewTrickVideoDTO implements NewTrickVideoDTOInterface
{
    /**
     * @var string|null
     *
     * @Assert\Type("string")
     * @Assert\NotNull(message="Une URL valide doit être renseignée.")
     */
    public $url;

    /**
     * NewTrickVideoDTO constructor.
     * @param string|null $url
     */
    public function __construct(?string $url = '')
    {
        $this->url = $url;
    }
}
