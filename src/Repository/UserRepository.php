<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends EntityRepository<User>
 *
 * @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends EntityRepository
{
    /**
     * @return User[]
     */
    public function getUsers(int $page, int $perPage): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('u')
            ->from($this->getClassName(), 'u')
            ->orderBy('u.id', 'DESC')
            ->setFirstResult($perPage * ($page - 1))
            ->setMaxResults($perPage);

        return $qb->getQuery()->getResult();
    }
}
