<?php

namespace App\Controller\Api\v1;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetTaskController extends AbstractController
{
    #[Route('/tasks/{id}', name: 'app_get_task', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function index(Task $task): JsonResponse
    {
        return new JsonResponse($task->toArray(), 200);
    }
}
