<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class UserRequest
{
    /**
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $password;

    public function __construct(
        string $email,
        string $password
    ) {
        $this->email = $email;
        $this->password = $password;
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
     * @return UserRequest
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return UserRequest
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
}
