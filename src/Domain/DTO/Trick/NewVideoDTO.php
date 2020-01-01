<?php

namespace App\Domain\DTO\Trick;

use App\Domain\DTO\Interfaces\Trick\VideoDTOInterface;
use Symfony\Component\Validator\Constraints as Assert;

class NewVideoDTO implements VideoDTOInterface
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
