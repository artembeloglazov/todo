<?php

namespace App\Controller\Api\v1;

use App\DTO\TaskInputDTO;
use App\Entity\Task;
use App\Manager\TaskManager;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostTaskController extends AbstractController
{
    public function __construct(
        private readonly TaskManager        $taskManager,
        private readonly ValidatorInterface $validator,
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route('/tasks/add', name: 'app_post_task', methods: ['POST'])]
    #[Route('/tasks/{id}', name: 'app_update_task', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function index(Request $request, ?Task $task = null): JsonResponse
    {
        $taskInputDTO = TaskInputDTO::fromRequest($request);
        $errors = [];
        foreach ($this->validator->validate($taskInputDTO) as $error) {
            $field = $error->getPropertyPath();
            $message = $error->getMessage();
            $errors[] = "$message: $field";
        }
        if ($errors) {
            return new JsonResponse([
                'success' => false,
                'errors' => $errors
            ], Response::HTTP_BAD_REQUEST);
        }
        if ($task) {
            $result = $this->taskManager->updateTask($task, $taskInputDTO);
        } else {
            $result = $this->taskManager->addTask($taskInputDTO);
        }

        return new JsonResponse(
            ['success' => (bool)$result],
            $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND
        );
    }
}
