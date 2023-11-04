<?php

namespace App\Repository;

use App\Entity\TrancheFiscal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TrancheFiscal>
 *
 * @method TrancheFiscal|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrancheFiscal|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrancheFiscal[]    findAll()
 * @method TrancheFiscal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrancheFiscalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrancheFiscal::class);
    }
    public function findTrancheFiscalChoices()
    {
        return $this->createQueryBuilder('tf')
            ->select('tf')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return TrancheFiscal[] Returns an array of TrancheFiscal objects
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

//    public function findOneBySomeField($value): ?TrancheFiscal
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
