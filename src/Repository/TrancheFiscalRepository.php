<?php

namespace App\Repository;

use App\Entity\Personne;
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
/*    public function findTrancheFiscalChoices()
    {
        return $this->createQueryBuilder('tf')
            ->select('tf')
            ->getQuery()
            ->getResult();
    }

    public function findTrancheFiscalChoicesByPersonne(Personne $personne)
    {
        return $this->createQueryBuilder('tf')
            ->andWhere('tf.nbPersonne = :val')
            ->setParameter('val', $personne)
            ->orderBy('tf.nbPersonne', 'ASC')
            ->getQuery()
            ->getResult();
    }*/
/*    public function findTrancheFiscalChoicesByPersonne(?Personne $personne)
    {
        if ($personne === null) {
            return []; // ou une autre valeur par défaut selon vos besoins
        }

        return $this->createQueryBuilder('tf')
            ->andWhere('tf.nbPersonne = :val')
            ->setParameter('val', $personne)
            ->orderBy('tf.nbPersonne', 'ASC')
            ->getQuery()
            ->getResult();
    }*/

    public function findTrancheFiscalChoicesByPersonne(?Personne $personne)
    {
        if ($personne === null) {
            return []; // ou une autre valeur par défaut selon vos besoins
        }

        $tranchesFiscales = $this->createQueryBuilder('tf')
            ->andWhere('tf.nbPersonne = :val')
            ->setParameter('val', $personne)
            ->orderBy('tf.nbPersonne', 'ASC')
            ->getQuery()
            ->getResult();

        $choices = [];
        foreach ($tranchesFiscales as $tranche) {
            $choices[$tranche->getId()] = $tranche->__toString();
        }

        return $choices;
    }








    public function findByNbPersonne($nbPersonne)
    {
        return $this->createQueryBuilder('tranchefiscal')
            ->join('tranchefiscal.nbPersonne', 'p')
            ->andWhere('p.nbPersonne = :nbPersonne')
            ->setParameter('nbPersonne', $nbPersonne)
            ->getQuery()
            ->getResult();
    }
    public function findByRegion($Region)
    {
        return $this->createQueryBuilder('tranchefiscal')
            ->join('tranchefiscal.Region', 'r')
            ->andWhere('r.id = :Region')
            ->setParameter('Region', $Region)
            ->getQuery()
            ->getResult();
    }
    public function findByNbPersonneByRegions($nbPersonne, $Region)
    {
        return $this->createQueryBuilder('tranchefiscal')
            ->join('tranchefiscal.nbPersonne', 'p')
            ->join('tranchefiscal.Regions', 'r')
            ->Where('p.nbPersonne = :nbPersonne')
            ->andWhere('r.id = :Regions')
            ->setParameter('nbPersonne', $nbPersonne)
            ->setParameter('Regions', $Region)
            ->getQuery()
            ->getResult();
    }

    public function findTrancheFiscalChoices()
    {
        return $this->createQueryBuilder('tf')
            ->andWhere('tf.nbPersonne IS NOT NULL') // Ajoutez cette condition pour ne sélectionner que les tranches liées à une personne
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
