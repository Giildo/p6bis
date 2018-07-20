<?php

namespace App\Domain\Model\Interfaces;


/**
 * Class Category
 * @package App\Domain\Model
 *
 * @ORM\Table(name="p6bis_category")
 * @ORM\Entity(repositoryClass="App\Domain\Repository\CategoryRepository")
 */
interface CategoryInterface
{
    /**
     * @return string
     */
    public function getName(): string;
}