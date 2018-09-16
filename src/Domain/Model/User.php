<?php

namespace App\Domain\Model;

use App\Domain\Model\Interfaces\PictureInterface;
use App\Domain\Model\Interfaces\UserInterface;
use DateInterval;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
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
     * @var PictureInterface
     *
     * @ORM\OneToOne(targetEntity="App\Domain\Model\Picture", cascade={"persist", "remove"})
     * @ORM\JoinColumn(referencedColumnName="name", name="picture_name")
     */
    private $picture;

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
     * {@inheritdoc}
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * {@inheritdoc}
     */
    public function getMail(): string
    {
        return $this->mail;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenDate(): ?DateTime
    {
        return $this->tokenDate;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getPicture(): ?PictureInterface
    {
        return $this->picture;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * {@inheritdoc}
     */
    public function changePassword(string $newPassword): void
    {
        $this->password = $newPassword;
    }

    /**
     * {@inheritdoc}
     */
    public function changeRole(array $newRole): void
    {
        $this->roles = $newRole;
    }

    /**
     * {@inheritdoc}
     */
    public function addRole(string $roleAdd): void
    {
        $this->roles[] = $roleAdd;
    }

    /**
     * {@inheritdoc}
     */
    public function createToken(TokenGeneratorInterface $tokenGenerator): void
    {
        $this->token = $tokenGenerator->generateToken();
        $this->tokenDate = (new DateTime())->add(
            new DateInterval('PT1H')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function deleteToken(): void
    {
        $this->token = null;
        $this->tokenDate = null;
    }

    /**
     * {@inheritdoc}
     */
    public function updateProfile(
        string $firstName,
        string $lastName,
        string $mail,
        ?PictureInterface $picture = null
    ): void {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->mail = $mail;
        $this->picture = $picture;
    }
}
