<?php

namespace App\DataFixtures;

use App\Entity\Calendar;
use App\Entity\Task;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(User::class)->findAll();
        $calendars = $manager->getRepository(Calendar::class)->findAll();
        for ($i = 0; $i < 100; $i++) {
            $task = new Task();
            $task->setTitle(uniqid());
            $task->setDescription(uniqid() . ' ' . uniqid());
            $days = rand(1, 20);
            $task->setDateStart((new DateTime())->modify("-$days day"));
            $task->setDateDue((new DateTime())->modify("+$days day"));
            $task->setAssigned($users[rand(0, count($users) - 1)]);
			$task->setCalendar($calendars[rand(0, count($calendars) - 1)]);
            $manager->persist($task);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CalendarFixtures::class, UserFixtures::class];
    }
}
