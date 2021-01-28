<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\PublicTask;
use App\Entity\Task;
use App\Entity\User;
use App\Serializer\PublicTaskSerializer;
use App\Generator\UuidGenerator;
use App\Transformer\TaskTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\ORMException;
use Exception;
use http\Exception\InvalidArgumentException;
use Ramsey\Uuid\Generator\CombGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController
{
    const UUID_LENGTH = 255;

    /**
     * @var PublicTaskSerializer
     */
    private $publicTaskSerializer;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var TaskTransformer
     */
    private $taskTransformer;

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
     * @return Response
     */
    public function createTask(Request $request): Response
    {
        try {
            $taskRequest = $this->publicTaskSerializer->deserializePublicTask(
                $request->getContent(false),
                PublicTaskSerializer::FORMAT_JSON
            );
        } catch (Exception $exception) {
            return new Response("Order request has wrong format");
        }

        if ($taskRequest->getHash() !== null) {
            return new Response("New orders cannot contain predefined hash");
        }

//        try {
//            $this->taskTransformer->fromPublicTask($taskRequest);
//        } catch (NoResultException | NonUniqueResultException $exception) {
//            return new Response('Internal server error');
//        }

        return new Response($this->publicTaskSerializer->serializePublicTask(
            $taskRequest,
            PublicTaskSerializer::FORMAT_JSON
        ));
    }
}
