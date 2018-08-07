<?php

namespace App\Domain\Builders;

use App\Application\Helpers\Interfaces\SluggerHelperInterface;
use App\Domain\Builders\Interfaces\TrickBuilderInterface;
use App\Domain\DTO\Interfaces\Trick\NewTrickDTOInterface;
use App\Domain\Model\Trick;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TrickBuilder implements TrickBuilderInterface
{
    /**
     * @var SluggerHelperInterface
     */
    private $sluggerHelper;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var Trick
     */
    private $trick;

    /**
     * TrickBuilder constructor.
     * @param SluggerHelperInterface $sluggerHelper
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        SluggerHelperInterface $sluggerHelper,
        TokenStorageInterface $tokenStorage
    ) {
        $this->sluggerHelper = $sluggerHelper;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function build(NewTrickDTOInterface $datas): self
    {
        $this->trick = new Trick(
            $this->sluggerHelper->slugify($datas->name),
            $datas->name,
            $datas->description,
            $datas->category,
            $this->tokenStorage->getToken()->getUser()
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTrick(): Trick
    {
        return $this->trick;
    }
}
