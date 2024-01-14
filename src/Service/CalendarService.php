<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Calendar;
use App\Manager\CalendarManager;
use DateTimeImmutable;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\ValueObject\DateTime;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Domain\ValueObject\UniqueIdentifier;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;
use Eluceo\iCal\Domain\Entity\Calendar as iCal;
use Psr\Log\LoggerInterface;
use Throwable;

class CalendarService {

	public function __construct(
		private EntityManagerInterface $entityManager,
		private CalendarManager $calendarManager,
		private LoggerInterface $logger,
		private string $icalFilePath
	) {
	}

	/**
	 * @throws Exception
	 */
	public function buildICal(Calendar $calendar) {
		try {
			$this->entityManager->getConnection()->beginTransaction();
			$events = [];
			foreach ($calendar->getTasks() as $task) {
				$events[] = (new Event(new UniqueIdentifier(sha1((string)$task->getId()))))
					->setSummary($task->getTitle())
					->setDescription($task->getDescription())
					->setOccurrence(
						new TimeSpan(
							new DateTime(DateTimeImmutable::createFromFormat(
								'Y-m-d H:i:s',
								$task->getDateStart()->format('Y-m-d H:i:s')
							), true),
							new DateTime(DateTimeImmutable::createFromFormat(
								'Y-m-d H:i:s',
								$task->getDateDue()->format('Y-m-d H:i:s')
							), true)
						)
					);
			}
			$iCalendar = new iCal($events);
			$componentFactory = new CalendarFactory();
			$iCalendarComponent = $componentFactory->createCalendar($iCalendar);
			file_put_contents($this->icalFilePath.'/'.$calendar->getFileName().'.ics', (string)$iCalendarComponent);

			$this->calendarManager->setExportFlag($calendar, true);
			$this->entityManager->persist($calendar);
			$this->entityManager->flush();
			$this->entityManager->getConnection()->commit();
		} catch (Throwable $e) {
			$code = $e->getCode();
			$message = $e->getMessage();
			$this->logger->error("[$code] Ошибка сборки ical-календаря: $message");
			$this->entityManager->getConnection()->rollBack();
		}
		$this->entityManager->clear();
		$this->entityManager->getConnection()->close();
	}
}