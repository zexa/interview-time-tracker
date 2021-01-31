<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Serializer\PublicTaskSerializer;
use App\Transformer\TaskTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskController
{
    private PublicTaskSerializer $publicTaskSerializer;
    private EntityManagerInterface $entityManager;
    private TaskTransformer $taskTransformer;

    public function __construct(
        PublicTaskSerializer $publicTaskSerializer,
        TaskTransformer $taskTransformer,
        EntityManagerInterface $entityManager
    ) {
        $this->publicTaskSerializer = $publicTaskSerializer;
        $this->taskTransformer = $taskTransformer;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/tasks", methods="post")
     * @param Request $request
     * @param UserInterface&User $user
     * @return Response
     */
    public function createTask(Request $request, UserInterface $user): Response
    {
        try {
            $taskRequest = $this->publicTaskSerializer->deserializePublicTask(
                $request->getContent(false),
                PublicTaskSerializer::FORMAT_JSON
            );
        } catch (Exception $exception) {
            return new Response('Invalid request format', 400);
        }

        if ($taskRequest->getHash() !== null) {
            return new Response(
                'New orders cannot contain predefined hash',
                400
            );
        }

        try {
            $task = $this->taskTransformer->fromPublicTask($taskRequest, $user);
        } catch (NoResultException | NonUniqueResultException $exception) {
            return new Response('Internal server error', 500);
        }

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        try {
            return new Response($this->publicTaskSerializer->serializePublicTask(
                $this->taskTransformer->intoPublicTask($task),
                PublicTaskSerializer::FORMAT_JSON
            ));
        } catch (NoResultException | NonUniqueResultException $exception) {
            return new Response('Internal server error', 500);
        }
    }
}
