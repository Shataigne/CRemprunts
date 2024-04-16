<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Vehicule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isFalse;

/**
 * @extends ServiceEntityRepository<Vehicule>
 *
 * @method Vehicule|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicule|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicule[]    findAll()
 * @method Vehicule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehiculeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicule::class);
    }




    //    /**
    //     * @return Vehicule[] Returns an array of Vehicule objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Vehicule
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findByFilters(SearchData $search)
    {
        $query = $this
            ->createQueryBuilder('v')
            ->select('c','m','v')
            ->leftjoin('v.centre', 'c')
            ->leftjoin('v.marque','m');

        if (!is_null($search->q)) {
            $query->andWhere('v.libelle LIKE :q')
                ->setParameter('q',"%{$search->q}%");
        }

        if (!empty($search->centres)) {
            $query->andWhere('c.id = :centre')
                ->setParameter('centre',$search->centres);

        }

        if (!empty($search->dispoNow)) {
            if ( $search->dispoNow === true) {
                $query->leftJoin('v.empruntVehicules', 'e', 'WITH', 'not((e.dateDebut > :dn AND e.dateFin > :dn) OR (e.dateDebut < :dn AND e.dateFin < :dn))')
                    ->andWhere('e.libelle is null')
                    ->setParameter('dn', new \DateTime());
            }
        }

        if (!empty($search->dispoLe)) {
            $query->leftJoin('v.empruntVehicules', 'e', 'WITH', 'not((e.dateDebut > :dl AND e.dateFin > :dl) OR (e.dateDebut < :dl AND e.dateFin < :dl))')
                ->andWhere('e.libelle is null')
                ->setParameter('dl', $search->dispoLe);
        }
        if (!empty($search->dispoMin) and !empty($search->dispoMax)) {
            $query->leftJoin('v.empruntVehicules', 'e', 'WITH', 'not((e.dateDebut > :da AND e.dateFin > :da) OR (e.dateDebut < :db AND e.dateFin < :db))')
                ->andWhere('e.libelle is null')
                ->setParameter('db', $search->dispoMin)
                ->setParameter('da', $search->dispoMax);
        }

        // Execute the query
        return $query->getQuery()->getResult();
    }


}
