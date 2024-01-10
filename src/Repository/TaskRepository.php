<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends EntityRepository<Task>
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository {
	public function __construct(ManagerRegistry $registry) {
		parent::__construct($registry, Task::class);
	}

	/**
	 * @return Task[] Returns an array of Task objects
	 */
	public function findByTitle($value): array {
		return $this->createQueryBuilder('t')
		            ->andWhere('t.title = :title')
		            ->setParameter('title', $value)
		            ->orderBy('t.id', 'ASC')
		            ->getQuery()
		            ->getResult();
	}

	public function findAllSortedByDateStart(){
		return $this->createQueryBuilder('t')
		            ->orderBy('t.dateStart', 'ASC')
		            ->getQuery()
		            ->getResult();
	}
}
