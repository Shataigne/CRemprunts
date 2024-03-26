<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Salle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Salle>
 *
 * @method Salle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Salle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Salle[]    findAll()
 * @method Salle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Salle::class);
    }

    //    /**
    //     * @return Salle[] Returns an array of Salle objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Salle
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findByCategorie($value): array
    {
        return $this->createQueryBuilder('s')
            ->select('s','c','e')
            ->leftJoin('s.centre', 'c')
            ->leftJoin('s.empruntSalles', 'e')
            ->andWhere('s.categorie = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }

    public function findByFilters(SearchData $search, string $type)
    {
        $query = $this
            ->createQueryBuilder('s')
            ->select('c','e','s')
            ->leftjoin('s.centre', 'c')
            ->leftJoin('s.empruntSalles','e')
            ->andWhere('s.categorie = :type')
            ->setParameter('type',"$type");

        if (!is_null($search->q)) {
            $query->andWhere('s.numero LIKE :q or s.batiment LIKE :q or s.etage LIKE :q')
                ->setParameter('q',"%{$search->q}%");
        }

        if (!empty($search->centres)) {
            $query->andWhere('c.id LIKE :centres')
                ->setParameter('centres',$search->centres);
        }

        if (!empty($search->dispoNow)) {
            if ( $search->dispoNow === true) {
                $query->andWhere('e.dateDebut != :dn or e.dateDebut is NULL' )
                    ->setParameter('dn', new \DateTime());
            }
        }

        // Execute the query
        return $query->getQuery()->getResult();
    }
}
