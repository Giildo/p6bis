<?php

namespace App\Domain\Model;

use App\Domain\Model\Interfaces\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * Class User
 *
 * @package App\Domain\Model
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
     * @ORM\Column(type="uuid", unique=true)
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
     * @ORM\Column(type="string", unique=true)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * User constructor.
     * @param UuidInterface $id
     * @param string $username
     * @param string $firstName
     * @param string $lastName
     * @param string $mail
     * @param string $password
     * @param callable $passwordEncoder
     */
    public function __construct(
        UuidInterface $id,
        string $username,
        string $firstName,
        string $lastName,
        string $mail,
        string $password,
        Callable $passwordEncoder
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->mail = $mail;
        $this->password = $passwordEncoder($password);
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
}
