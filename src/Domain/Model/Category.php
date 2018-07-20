<?php

namespace App\Domain\Model;
use App\Application\Helpers\Interfaces\SluggerHelperInterface;
use App\Domain\Model\Interfaces\CategoryInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Category
 * @package App\Domain\Model
 *
 * @ORM\Table(name="p6bis_category")
 * @ORM\Entity(repositoryClass="App\Domain\Repository\CategoryRepository")
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
     * @param string $name
     * @param SluggerHelperInterface $slugger
     */
    public function __construct(string $name, SluggerHelperInterface $slugger)
    {
        $this->name = $name;

        $this->createSlug($slugger, $name);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    public function createSlug(SluggerHelperInterface $slugger, string $name): void
    {
        $this->slug = $slugger->slugify($name);
    }
}
