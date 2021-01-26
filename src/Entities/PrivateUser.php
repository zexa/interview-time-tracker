<?php

declare(strict_types=1);

namespace App\Entities;

class PrivateUser
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $email;

    public function __construct(
        string $id,
        string $email
    ) {
        $this->id = $id;
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

}