<?php

namespace App\Repository;

use App\Entity\Picture;
use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Picture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Picture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Picture[]    findAll()
 * @method Picture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PictureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Picture::class);
    }

    // WE ARE USED FOR THE EDIT OF A TRICK 
    public function findPicturesTrick(Trick $trick): QueryBuilder
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.trick = :val')
            ->setParameter('val', $trick->getId());

        return $qb;
    }
}
