<?php

namespace App\Repository;

use App\Entity\Menage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Menage>
 *
 * @method Menage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Menage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Menage[]    findAll()
 * @method Menage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menage::class);
    }

//    /**
//     * @return Menage[] Returns an array of Menage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Menage
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
