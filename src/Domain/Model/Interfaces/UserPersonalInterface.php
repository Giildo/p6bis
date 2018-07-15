<?php

namespace App\Domain\Model\Interfaces;

use Ramsey\Uuid\UuidInterface;

interface UserPersonalInterface
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
}