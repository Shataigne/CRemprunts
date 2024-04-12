<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Vehicule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function PHPUnit\Framework\isEmpty;

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
            ->select('c','m','v','e')
            ->leftjoin('v.centre', 'c')
            ->leftJoin('v.empruntVehicules','e')
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
                $query->andWhere('e.dateDebut < :dn and e.dateFin < :dn')
                    ->orWhere('e.dateDebut > :dn and e.dateFin > :dn' )
                    ->orWhere('v.empruntVehicules is empty' )
                    ->setParameter('dn', new \DateTime());
            }
        }

        // Execute the query
        return $query->getQuery()->getResult();
    }


}
