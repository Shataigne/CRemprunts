<?php

namespace App\Repository;

use App\Entity\EmpruntSalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmpruntSalle>
 *
 * @method EmpruntSalle|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmpruntSalle|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmpruntSalle[]    findAll()
 * @method EmpruntSalle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmpruntSalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmpruntSalle::class);
    }

    //    /**
    //     * @return EmpruntSalle[] Returns an array of EmpruntSalle objects
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

    //    public function findOneBySomeField($value): ?EmpruntSalle
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
