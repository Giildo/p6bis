<?php

namespace App\Domain\Model;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

/**
 * Class User
 *
 * @ORM\Table(name="p6bis_user")
 * @ORM\Entity(repositoryClass="App\Domain\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=43, nullable=true)
     */
    private $token;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $tokenDate;

    /**
     * User constructor.
     * @param string $username
     * @param string $firstName
     * @param string $lastName
     * @param string $mail
     * @param string $password
s     */
    public function __construct(
        string $username,
        string $firstName,
        string $lastName,
        string $mail,
        string $password
    ) {
        $this->username = $username;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->mail = $mail;
        $this->password = $password;
        $this->roles[] = 'ROLE_USER';
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getMail(): string
    {
        return $this->mail;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return null|string
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @return DateTime|null
     */
    public function getTokenDate(): ?DateTime
    {
        return $this->tokenDate;
    }

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
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @param string $newPassword
     * @return void
     */
    public function changePassword(string $newPassword): void
    {
        $this->password = $newPassword;
    }

    /**
     * @param array $newRole
     * @return void
     */
    public function changeRole(array $newRole): void
    {
        $this->roles = $newRole;
    }

    /**
     * @param string $roleAdd
     * @return void
     */
    public function addRole(string $roleAdd): void
    {
        $this->roles[] = $roleAdd;
    }

    /**
     * @param TokenGeneratorInterface $tokenGenerator
     * @return void
     */
    public function createToken(TokenGeneratorInterface $tokenGenerator): void
    {
        $this->token = $tokenGenerator->generateToken();
        $this->tokenDate = new DateTime();
    }

    /**
     * @return void
     */
    public function deleteToken(): void
    {
        $this->token = null;
        $this->tokenDate = null;
    }
}
