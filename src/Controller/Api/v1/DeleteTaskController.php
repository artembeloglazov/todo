<?php

namespace App\Controller\Api\v1;

use App\Entity\Task;
use App\Manager\TaskManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteTaskController extends AbstractController
{
    public function __construct(
        private readonly TaskManager $taskManager,
    )
    {
    }

    #[Route('/tasks/{id}', name: 'app_delete_task', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function index(Task $task): JsonResponse
    {
        $result = $this->taskManager->deleteTask($task);

        return new JsonResponse(
            ['success' => $result],
            $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND
        );
    }
}
