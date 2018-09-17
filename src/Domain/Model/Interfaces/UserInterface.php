<?php

namespace App\Domain\Model\Interfaces;

use DateTime;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface as UserInterfaceSymfony;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

interface UserInterface extends UserInterfaceSymfony
{
    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface;

    /**
     * @return string
     */
    public function getUsername(): string;

    /**
     * @return string
     */
    public function getFirstName(): string;

    /**
     * @return string
     */
    public function getLastName(): string;

    /**
     * @return string
     */
    public function getMail(): string;

    /**
     * @return string
     */
    public function getPassword(): string;

    /**
     * @return null|string
     */
    public function getToken(): ?string;

    /**
     * @return DateTime|null
     */
    public function getTokenDate(): ?DateTime;

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return array (Role|string)[] The user roles
     */
    public function getRoles(): array;

    /**
     * @return PictureInterface|null
     */
    public function getPicture(): ?PictureInterface;

    /**
     * @param string $newPassword
     * @return void
     */
    public function changePassword(string $newPassword): void;

    /**
     * @param array $newRole
     * @return void
     */
    public function changeRole(array $newRole): void;

    /**
     * @param string $roleAdd
     * @return void
     */
    public function addRole(string $roleAdd): void;

    /**
     * @param TokenGeneratorInterface $tokenGenerator
     * @return void
     * @throws \Exception
     */
    public function createToken(TokenGeneratorInterface $tokenGenerator): void;

    /**
     * @return void
     */
    public function deleteToken(): void;

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $mail
     * @param PictureInterface $picture
     * @return void
     */
    public function updateProfile(
        string $firstName,
        string $lastName,
        string $mail,
        ?PictureInterface $picture = null
    ): void;

    /**
     *
     */
    public function deletePicture(): void;
}
