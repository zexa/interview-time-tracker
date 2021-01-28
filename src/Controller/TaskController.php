<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entities\PublicTask;
use App\Serializer\PublicTaskSerializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController
{
    /**
     * @var PublicTaskSerializer
     */
    private $publicTaskSerializer;

    public function __construct(
        PublicTaskSerializer $publicTaskSerializer
    ) {
        $this->publicTaskSerializer = $publicTaskSerializer;
    }

    /**
     * @Route("/tasks", methods="post")
     * @param Request $request
     * @return Response
     */
    public function createTask(Request $request): Response
    {
        // validate
        $task = $this->publicTaskSerializer->deserializePublicTask(
            $request->getContent(false),
            PublicTaskSerializer::FORMAT_JSON
        );

        // save

        // respond
        return new Response($this->publicTaskSerializer->serializePublicTask(
            $task,
            PublicTaskSerializer::FORMAT_JSON
        ));
    }
}
