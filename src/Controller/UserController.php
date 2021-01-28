<?php

declare(strict_types=1);

namespace App\Controller;

use App\Factory\UserFactory;
use App\Repository\UserRepository;
use App\Serializer\UserRequestSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController
{
    const PASSWORD_MIN_LENGTH = 4;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UserRequestSerializer
     */
    private $userRequestSerializer;
    /**
     * @var UserFactory
     */
    private $userFactory;
    /**
     * @var JWTTokenManagerInterface
     */
    private $jwtManager;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRequestSerializer $userRequestSerializer,
        UserFactory $userFactory,
        JWTTokenManagerInterface $jwtManger,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $userPasswordEncoder
    ) {
        $this->entityManager = $entityManager;
        $this->userRequestSerializer = $userRequestSerializer;
        $this->userFactory = $userFactory;
        $this->jwtManager = $jwtManger;
        $this->userRepository = $userRepository;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @return Response
     */
    public function createUser(Request $request): Response
    {
        try {
            $userRequest = $this->userRequestSerializer->deserializeUserRequest(
                $request->getContent(false),
                UserRequestSerializer::FORMAT_JSON
            );
        } catch (Exception $exception) {
            return new Response('Invalid request format', 400);
        }

        try {
            $user = $this->userRepository->findOneByEmail($userRequest->getEmail());
        } catch (NonUniqueResultException $exception) {
            return new Response('Internal server error', 500);
        }

        if ($user !== null) {
            return new Response('User with such email already exists', 400);
        }

        if (mb_strlen($userRequest->getPassword()) < self::PASSWORD_MIN_LENGTH) {
            return new Response(
                'User password must contain at least ' . self::PASSWORD_MIN_LENGTH . ' characters',
                400
            );
        }

        $user = $this->userFactory->createFromUserRequest($userRequest);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->getJwtToken($user);
    }

    /**
     * @Route("/login", name="login")
     * @param UserInterface $user
     * @return Response
     */
    private function getJwtToken(UserInterface $user): Response
    {
        return new Response($this->jwtManager->create($user));
    }
}
