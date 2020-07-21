<?php

namespace App\Repository;

use App\Entity\QuizImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QuizImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizImage[]    findAll()
 * @method QuizImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizImage::class);
    }

    // /**
    //  * @return QuizImage[] Returns an array of QuizImage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QuizImage
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
