<?php

namespace App\Repository;

use App\Entity\Video;
use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Video|null find($id, $lockMode = null, $lockVersion = null)
 * @method Video|null findOneBy(array $criteria, array $orderBy = null)
 * @method Video[]    findAll()
 * @method Video[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Video::class);
    }

    // WE ARE USED FOR THE EDIT OF A TRICK 
    public function findVideosTrick(Trick $trick): QueryBuilder
    {
        $qb = $this->createQueryBuilder('v')
            ->where('v.trick = :val')
            ->setParameter('val', $trick->getId());

        return $qb;
    }
}
