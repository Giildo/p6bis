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
     * @param NewTrickDTOInterface $datas
     * @return Trick
     */
    public function build(NewTrickDTOInterface $datas): Trick
    {
        return new Trick(
            $datas->name,
            $datas->description,
            $this->sluggerHelper,
            $datas->category,
            $this->tokenStorage->getToken()->getUser()
        );
    }
}
