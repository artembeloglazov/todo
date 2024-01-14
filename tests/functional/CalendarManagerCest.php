<?php


namespace App\Tests\Functional;

use App\Entity\Calendar;
use App\Repository\CalendarRepository;
use App\Service\CalendarService;
use App\Tests\FunctionalTester;
use Doctrine\DBAL\Exception;

class CalendarManagerCest {
	public function _before(FunctionalTester $I) {
	}

	/**
	 * @throws Exception
	 */
	public function buildSuccessfullTest(FunctionalTester $I) {
		$calendarRepositary = $I->grabRepository(CalendarRepository::class);
		/** @var Calendar $randomCalendar */
		$randomCalendar = $calendarRepositary->findOneBy(['isExport' => false]);
		/** @var CalendarService $calendarService */
		$calendarService = $I->grabService(CalendarService::class);
		$calendarService->buildICal($randomCalendar);
		$I->assertTrue($randomCalendar->isIsExport());
	}
}
