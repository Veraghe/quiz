<?php

namespace App\Repository;

use App\Entity\ReponseUtilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReponseUtilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReponseUtilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReponseUtilisateur[]    findAll()
 * @method ReponseUtilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseUtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReponseUtilisateur::class);
    }

    public function getReponseForQuestion($question, $utilisateur){
    // select ru.question_id, ru.utilisateur_id, ru.reponse_id from reponse_utilisateur as ru 
        $qb = $this->createQueryBuilder('ru')
            ->join('ru.id_question', 'q')
            ->join('ru.resultat_id', 'res')
            ->join('res.utilisateur_id', 'partQuiz')
            ->join('partQuiz.utilisateur', 'part')
            ->where('q.id = ?1')
            ->andWhere('part.id = ?3')
            ->setParameter(1, $question)
            ->setParameter(2, $utilisateur)
            ->getQuery();

        return $qb->getArrayResult();
    }

    // select * from reponse_utilisateur where date = curdate()
    // public function findReponseByDate()
    // {
    //     return $this->createQueryBuilder('reponse_utilisateur')
    //         ->Where('date = curdate()')
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }

    // /**
    //  * @return ReponseUtilisateur[] Returns an array of ReponseUtilisateur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reponse
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
