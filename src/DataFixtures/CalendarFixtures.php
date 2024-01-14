<?php

namespace App\DataFixtures;

use App\Entity\Calendar;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CalendarFixtures extends Fixture {
	public function load(ObjectManager $manager): void {
		for ($i = 0; $i < 5; $i++) {
			$calendar = new Calendar();
			$name = md5(uniqid(rand(), true));
			$calendar->setName($name);
			$calendar->setCreatedAt();
			$calendar->setUpdatedAt();
			$calendar->setFileName($name);
			$calendar->setIsExport(array_rand([true, false]));
			$manager->persist($calendar);
		}

		$manager->flush();
	}
}
