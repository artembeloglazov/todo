<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Calendar;
use App\Manager\CalendarManager;
use DateTimeImmutable;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\ValueObject\Date;
use Eluceo\iCal\Domain\ValueObject\SingleDay;
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
//		try {
			$this->entityManager->getConnection()->beginTransaction();

			// 1. Create Event domain entity
			$event = (new Event())
				->setSummary('Christmas Eve')
				->setDescription('Lorem Ipsum Dolor...')
				->setOccurrence(
					new SingleDay(
						new Date(
							DateTimeImmutable::createFromFormat('Y-m-d', '2030-12-24')
						)
					)
				);

			// 2. Create Calendar domain entity
			$iCalendar = new iCal([$event]);

			// 3. Transform domain entity into an iCalendar component
			$componentFactory = new CalendarFactory();
			$iCalendarComponent = $componentFactory->createCalendar($iCalendar);
			file_put_contents($this->icalFilePath . '/' . $calendar->getFileName() . '.ics', (string) $iCalendarComponent);

			$this->calendarManager->setExportFlag($calendar, true);
			$this->entityManager->persist($calendar);
			$this->entityManager->flush();
			$this->entityManager->getConnection()->commit();
//		} catch (Throwable $e) {
//			$code = $e->getCode();
//			$message = $e->getMessage();
//			$this->logger->error("[$code] Ошибка сборки ical-календаря: $message");
//			$this->entityManager->getConnection()->rollBack();
//		}
		$this->entityManager->clear();
		$this->entityManager->getConnection()->close();
	}
}