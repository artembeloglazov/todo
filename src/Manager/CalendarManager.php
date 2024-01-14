<?php

namespace App\Manager;

use App\Entity\Calendar;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class CalendarManager {
	public function __construct(
		private readonly EntityManagerInterface $entityManager,
		private readonly LoggerInterface $logger
	) {
	}

	public function addTask(): int {
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

	public function setExportFlag(Calendar $calendar, bool $value): bool {
		try {
			$calendar->setIsExport($value);
			$this->entityManager->persist($calendar);
			$this->entityManager->flush();
		} catch (Throwable $e) {
			$code = $e->getCode();
			$message = $e->getMessage();
			$this->logger->error("[$code] Ошибка обновления календаря: $message");

			return false;
		}

		return !$this->entityManager->getUnitOfWork()->isEntityScheduled($calendar);
	}
}