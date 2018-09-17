<?php

namespace App\Application\Handlers\Forms\User;

use App\Application\Handlers\Interfaces\Forms\User\ProfilePictureHandlerInterface;
use App\Application\Helpers\PictureSaveHelper;
use App\Domain\Builders\Interfaces\PictureBuilderInterface;
use App\Domain\DTO\User\ProfilePictureDTO;
use App\Domain\Model\Interfaces\UserInterface;
use App\Domain\Repository\UserRepository;
use Symfony\Component\Form\FormInterface;

class ProfilePictureHandler implements ProfilePictureHandlerInterface
{
    /**
     * @var PictureBuilderInterface
     */
    private $builder;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var PictureSaveHelper
     */
    private $saveHelper;

    /**
     * ProfilePictureHandler constructor.
     * @param PictureBuilderInterface $builder
     * @param UserRepository $userRepository
     * @param PictureSaveHelper $saveHelper
     */
    public function __construct(
        PictureBuilderInterface $builder,
        UserRepository $userRepository,
        PictureSaveHelper $saveHelper
    ) {
        $this->builder = $builder;
        $this->userRepository = $userRepository;
        $this->saveHelper = $saveHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(FormInterface $form, UserInterface $user): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ProfilePictureDTO $dto */
            $dto = $form->getData();

            $picture = null;

            if (!is_null($dto->profilePicture->picture)) {
                $picture = $this->builder->build($dto->profilePicture, null, 0, true, $user)
                    ->getPicture();

                if (!is_null($user->getPicture())) {
                    $picture = $user->getPicture();
                    $name = "{$picture->getName()}.{$picture->getExtension()}";
                    unlink(__DIR__ . '/../../../../../public/pic/users/' . $name);
                }
            } elseif (!is_null($user->getPicture())) {
                $picture = $user->getPicture();
            }

            $user->updateProfile(
                $dto->firstName,
                $dto->lastName,
                $dto->mail,
                $picture
            );

            $this->userRepository->saveUser($user);

            if (!is_null($dto->profilePicture->picture)) {
                $this->saveHelper->save([[$picture, $dto->profilePicture->picture]], 'users');
            }

            return true;
        }

        return false;
    }
}
