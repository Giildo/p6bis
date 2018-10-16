<?php

namespace App\Domain\Model;
use App\Domain\Model\Interfaces\CategoryInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Category
 * @package App\Domain\Model
 *
 * @ORM\Table(name="p6bis_category")
 * @ORM\Entity()
 */
class Category implements CategoryInterface
{
    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\Column(type="string", length=50)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * Category constructor.
     * @param string $slug
     * @param string $name
     */
    public function __construct(
        string $slug,
        string $name
    ) {
        $this->slug = $slug;
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getSlug(): string
    {
        return $this->slug;
    }
}
