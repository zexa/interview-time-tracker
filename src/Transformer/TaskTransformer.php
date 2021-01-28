<?php

declare(strict_types=1);

namespace App\Transformer;

use App\Entity\Comment;
use App\Entity\PublicTask;
use App\Entity\Task;
use App\Entity\User;
use App\Generator\UuidGenerator;
use App\Repository\TaskRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class TaskTransformer
{
    /**
     * @var TaskRepository
     */
    private $taskRepository;
    /**
     * @var UuidGenerator
     */
    private $uuidGenerator;

    public function __construct(
        TaskRepository $taskRepository,
        UuidGenerator $uuidGenerator
    ) {
        $this->taskRepository = $taskRepository;
        $this->uuidGenerator= $uuidGenerator;
    }

    /**
     * @param PublicTask $publicTask
     * @param User $user
     * @return Task
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function fromPublicTask(PublicTask $publicTask, User $user): Task
    {
        if ($publicTask->getHash() !== null) {
            return $this->taskRepository->findByHash($publicTask->getHash());
        }

        $task = (new Task())
            ->setHash($this->uuidGenerator->generateString())
            ->setTitle($publicTask->getTitle())
            ->setDate($publicTask->getDate())
            ->setTimeSpent($publicTask->getDuration())
        ;

        foreach($publicTask->getComments() as $comment) {
            $task->addComment(
                (new Comment())
                    ->setHash($this->uuidGenerator->generateString())
                    ->setTask($task)
                    ->setContent($comment)
            );
        }

        return $task;
    }

    public function intoPublicTask(Task $task): PublicTask
    {
        foreach ($task->getComments() as $comment) {
            $comments[] = $comment->getContent();
        }

        return new PublicTask(
            $task->getTitle(),
            $comments ?? [],
            $task->getDate(),
            $task->getTimeSpent(),
            $task->getHash()
        );
    }
}
