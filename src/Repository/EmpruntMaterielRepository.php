<?php

namespace App\Repository;

use App\Entity\EmpruntMateriel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmpruntMateriel>
 *
 * @method EmpruntMateriel|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmpruntMateriel|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmpruntMateriel[]    findAll()
 * @method EmpruntMateriel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmpruntMaterielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmpruntMateriel::class);
    }

    //    /**
    //     * @return EmpruntMateriel[] Returns an array of EmpruntMateriel objects
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

    //    public function findOneBySomeField($value): ?EmpruntMateriel
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
