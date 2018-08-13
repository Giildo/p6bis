<?php

namespace App\Domain\DTO\Trick;

use App\Domain\DTO\Interfaces\Trick\TrickNewVideoDTOInterface;
use Symfony\Component\Validator\Constraints as Assert;

class NewTrickNewVideoDTO implements TrickNewVideoDTOInterface
{
    /**
     * @var string|null
     *
     * @Assert\Type("string")
     * @Assert\NotNull(message="Une URL valide doit être renseignée.")
     */
    public $url;

    /**
     * {@inheritdoc}
     */
    public function __construct(?string $url = '')
    {
        $this->url = $url;
    }
}
