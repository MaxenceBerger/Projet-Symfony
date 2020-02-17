<?php

namespace App\Repository;

use App\Entity\CommentTeam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CommentTeam|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentTeam|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentTeam[]    findAll()
 * @method CommentTeam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentTeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentTeam::class);
    }

    // /**
    //  * @return CommentEvent[] Returns an array of CommentEvent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommentEvent
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
