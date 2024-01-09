<?php

namespace App\Manager;

use App\DTO\TaskInputDTO;
use App\Entity\Task;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class TaskManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface        $logger
    )
    {
    }

    public function addTask(TaskInputDTO $addTaskDTO): int
    {
        try {
            $task = new Task();
            $task->setTitle($addTaskDTO->getTitle());
            $task->setDescription($addTaskDTO->getDescription());
            if ($addTaskDTO->getDateDue()) {
                $task->setDateDue(new DateTimeImmutable($addTaskDTO->getDateDue()));
            }
            $this->entityManager->persist($task);
            $this->entityManager->flush();
        } catch (Throwable $e) {
            $code = $e->getCode();
            $message = $e->getMessage();
            $this->logger->error("[$code] Ошибка создания задачи: $message");

            return 0;
        }

        return $task->getId();
    }

    public function updateTask(Task $task, TaskInputDTO $taskInputDTO): bool
    {
        try {
            if ($taskInputDTO->getTitle()) {
                $task->setTitle($taskInputDTO->getTitle());
            }
            if ($taskInputDTO->getDescription()) {
                $task->setDescription($taskInputDTO->getDescription());
            }
            if ($taskInputDTO->getDateDue()) {
                $task->setDateDue(new DateTimeImmutable($taskInputDTO->getDateDue()));
            }
            $this->entityManager->persist($task);
            $this->entityManager->flush();
        } catch (Throwable $e) {
            $code = $e->getCode();
            $message = $e->getMessage();
            $this->logger->error("[$code] Ошибка обновления задачи: $message");

            return false;
        }

        return !$this->entityManager->getUnitOfWork()->isEntityScheduled($task);
    }

    public function deleteTask(Task $task): bool
    {
        try {
            $this->entityManager->remove($task);
            $this->entityManager->flush();
        } catch (Throwable $e) {
            $code = $e->getCode();
            $message = $e->getMessage();
            $this->logger->error("[$code] Ошибка удаления задачи: $message");

            return false;
        }

        return true;
    }
}