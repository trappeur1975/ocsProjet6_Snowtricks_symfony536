<?php

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Trick|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trick|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trick[]    findAll()
 * @method Trick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }

    /**
     * @return Trick[] Returns an array of Trick objects for the pagination
     */

    public function paginatedTrick(int $pageTrick, int $numberTrick)
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.id', 'ASC')
            ->setFirstResult(($pageTrick - 1) * $numberTrick)
            ->setMaxResults($numberTrick)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return int Returns the total number of tricks  for the pagination
     */
    public function countTrick()
    {
        return $this->createQueryBuilder('t')
            ->select("COUNT(t.id)")
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return int Return the total number of tricks
     */
    public function countAllTrick()
    {
        $QueryBuilder = $this->createQueryBuilder('t');
        $QueryBuilder->select('COUNT(t.id) as value');
        return $QueryBuilder->getQuery()->getSingleScalarResult();
    }
}
