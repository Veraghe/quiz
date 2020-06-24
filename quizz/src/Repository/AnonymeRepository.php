<?php

namespace App\Repository;

use App\Entity\Anonyme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Anonyme|null find($id, $lockMode = null, $lockVersion = null)
 * @method Anonyme|null findOneBy(array $criteria, array $orderBy = null)
 * @method Anonyme[]    findAll()
 * @method Anonyme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnonymeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Anonyme::class);
    }

    // /**
    //  * @return Anonyme[] Returns an array of Anonyme objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Anonyme
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
