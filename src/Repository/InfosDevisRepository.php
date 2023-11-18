<?php

namespace App\Repository;

use App\Entity\InfosDevis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InfosDevis>
 *
 * @method InfosDevis|null find($id, $lockMode = null, $lockVersion = null)
 * @method InfosDevis|null findOneBy(array $criteria, array $orderBy = null)
 * @method InfosDevis[]    findAll()
 * @method InfosDevis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfosDevisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InfosDevis::class);
    }
    public function findTrancheFiscalChoicesByPersonne(Personne $personne)
    {
        return $this->createQueryBuilder('tf')
            ->where(':personne MEMBER OF tf.nbPersonne')
            ->setParameter('personne', $personne)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return InfosDevis[] Returns an array of InfosDevis objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InfosDevis
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
