<?php

namespace App\Repository;

use App\Entity\EmpruntVehicule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmpruntVehicule>
 *
 * @method EmpruntVehicule|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmpruntVehicule|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmpruntVehicule[]    findAll()
 * @method EmpruntVehicule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmpruntVehiculeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmpruntVehicule::class);
    }

    //    /**
    //     * @return EmpruntVehicule[] Returns an array of EmpruntVehicule objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?EmpruntVehicule
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
