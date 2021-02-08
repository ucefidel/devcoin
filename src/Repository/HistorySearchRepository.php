<?php

namespace App\Repository;

use App\Entity\HistorySearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HistorySearch|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistorySearch|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistorySearch[]    findAll()
 * @method HistorySearch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistorySearchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistorySearch::class);
    }

    // /**
    //  * @return HistorySearch[] Returns an array of HistorySearch objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HistorySearch
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
