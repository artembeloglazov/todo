<?php

namespace App\DataFixtures;

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
        for ($i = 0; $i < 100; $i++) {
            $task = new Task();
            $task->setTitle(uniqid());
            $task->setDescription(uniqid() . ' ' . uniqid());
            $days = rand(1, 20);
            $task->setDateDue((new DateTime())->modify("+$days day"));
            $task->setAssigned($users[rand(0, count($users) - 1)]);
            $manager->persist($task);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
