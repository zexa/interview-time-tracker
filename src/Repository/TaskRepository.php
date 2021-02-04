<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * @param string $hash
     * @return Task
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findByHash(string $hash): Task
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.hash = :hash')
            ->setParameter('hash', $hash)
            ->getQuery()
            ->getSingleResult()
        ;
    }

    public function findByUserAndDateRange(User $user, string $dateFrom, string $dateTo): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.owner = :owner')
            ->andWhere('t.date BETWEEN :date_from AND :date_to')
            ->setParameter('owner', $user)
            ->setParameter('date_from', $dateFrom)
            ->setParameter('date_to', $dateTo)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findPagedByUser(User $user, int $page, int $pageSize): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.owner = :owner')
            ->setFirstResult($page * $pageSize)
            ->setMaxResults($pageSize)
            ->setParameter('owner', $user)
            ->getQuery()
            ->getResult()
        ;
    }
}
