<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\User;
use App\Entity\UserRequest;
use App\Generator\UuidGenerator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFactory
{
    private UuidGenerator $uuidGenerator;
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(
        UuidGenerator $uuidGenerator,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->uuidGenerator = $uuidGenerator;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function createFromUserRequest(UserRequest $userRequest): User
    {
        $user = new User();

        return $user
            ->setHash($this->uuidGenerator->generateString())
            ->setEmail($userRequest->getEmail())
            ->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $userRequest->getPassword()
            ))
        ;
    }
}
