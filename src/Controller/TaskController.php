<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\GetTasksParameters;
use App\Entity\User;
use App\Repository\TaskRepository;
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
    const GET_TASKS_QUERY_PAGE = 'page';
    const GET_TASKS_QUERY_PAGE_SIZE = 'page_size';

    private PublicTaskSerializer $publicTaskSerializer;
    private EntityManagerInterface $entityManager;
    private TaskTransformer $taskTransformer;
    private TaskRepository $taskRepository;

    public function __construct(
        PublicTaskSerializer $publicTaskSerializer,
        TaskTransformer $taskTransformer,
        EntityManagerInterface $entityManager,
        TaskRepository $taskRepository
    ) {
        $this->publicTaskSerializer = $publicTaskSerializer;
        $this->taskTransformer = $taskTransformer;
        $this->entityManager = $entityManager;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @Route("/tasks", methods={"GET"})
     * @param Request $request
     * @param UserInterface|User $user
     * @return Response
     */
    public function getTasks(Request $request, UserInterface $user): Response
    {
        $taskParameters = new GetTasksParameters(
            $user,
            $request->query->get(self::GET_TASKS_QUERY_PAGE) ?? '0',
            $request->query->get(self::GET_TASKS_QUERY_PAGE_SIZE) ?? '50'
        );

        if ($taskParameters->getPageSize() > 500) {
            return new Response('Invalid request format', 400);
        }

        $tasks = $this->taskRepository->findPagedByUser(
            $taskParameters->getUser(),
            $taskParameters->getPage(),
            $taskParameters->getPageSize()
        );

        foreach ($tasks as $task) {
            $publicTasks[] = $this->taskTransformer->intoPublicTask($task);
        }

        return new Response(
            $this->publicTaskSerializer->serializePublicTaskCollection(
                $publicTasks ?? [],
                PublicTaskSerializer::FORMAT_JSON
            )
        );
    }

    /**
     * @Route("/tasks", methods={"POST"})
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

        return new Response($this->publicTaskSerializer->serializePublicTask(
            $this->taskTransformer->intoPublicTask($task),
            PublicTaskSerializer::FORMAT_JSON
        ));
    }
}
