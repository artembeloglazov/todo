<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<Task>
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends EntityRepository
{

    /**
     * @return Task[] Returns an array of Task objects
     */
    public function findByTitle($value): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.title = :title')
            ->setParameter('title', $value)
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
