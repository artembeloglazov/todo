<?php

namespace App\Manager;

use App\Entity\Calendar;
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

    public function addTask(): int
    {
        try {
            $calendar = new Calendar();
            $this->entityManager->persist($calendar);
            $this->entityManager->flush();
        } catch (Throwable $e) {
            $code = $e->getCode();
            $message = $e->getMessage();
            $this->logger->error("[$code] Ошибка создания календаря: $message");

            return 0;
        }

        return $calendar->getId();
    }

}