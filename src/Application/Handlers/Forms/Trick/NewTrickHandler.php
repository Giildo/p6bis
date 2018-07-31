<?php

namespace App\Application\Handlers\Forms\Trick;

use App\Application\Handlers\Interfaces\Forms\Trick\NewTrickHandlerInterface;
use App\Domain\Builders\Interfaces\TrickBuilderInterface;
use App\Domain\Model\Trick;
use App\Domain\Repository\TrickRepository;
use Symfony\Component\Form\FormInterface;

class NewTrickHandler implements NewTrickHandlerInterface
{
    /**
     * @var TrickBuilderInterface
     */
    private $builder;
    /**
     * @var TrickRepository
     */
    private $repository;

    /**
     * NewTrickHandler constructor.
     * @param TrickBuilderInterface $builder
     * @param TrickRepository $repository
     */
    public function __construct(
        TrickBuilderInterface $builder,
        TrickRepository $repository
    ) {
        $this->builder = $builder;
        $this->repository = $repository;
    }

    /**
     * @param FormInterface $form
     * @return Trick|null
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(FormInterface $form): ?Trick
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $datas = $form->getData();

            $trick = $this->builder->build($datas);

            $this->repository->saveTrick($trick);

            return $trick;
        }

        return null;
    }
}
