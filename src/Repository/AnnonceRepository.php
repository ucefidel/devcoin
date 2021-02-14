<?php

namespace App\Repository;

use App\Entity\Annonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonce::class);
    }

    /**
     * @param string $userId
     * @return array
     */
    public function findByUser(string $userId): array
    {
        return $this->createQueryBuilder('a')
            ->join('a.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $keyword
     * @param $localisation
     * @return array
     */
    public function findBySearcher($keyword, $localisation = ""): array
    {
        $sqlQuery = $this->createQueryBuilder('a')
            ->where('a.title like :keyword')
            ->setParameter('keyword', '%' . $keyword . '%');

        if ($localisation !== "") {
            $sqlQuery = $sqlQuery->andWhere('a.localisation like :localisation')
                ->setParameter('localisation', '%' . $localisation . '%');
        }
        return $sqlQuery->getQuery()
            ->getResult();
    }

}
