<?php

namespace App\Repository;

use App\Entity\TypeDevis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeDevis>
 *
 * @method TypeDevis|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeDevis|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeDevis[]    findAll()
 * @method TypeDevis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeDevisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeDevis::class);
    }

//    /**
//     * @return TypeDevis[] Returns an array of TypeDevis objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TypeDevis
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
