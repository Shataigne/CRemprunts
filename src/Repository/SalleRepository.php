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

    public function findByFilters(SearchData $search, ?string $type)
    {
        $query = $this
            ->createQueryBuilder('s')
            ->select('c','e','s')
            ->leftjoin('s.centre', 'c')
            ->leftJoin('s.empruntSalles','e');

        if (!empty($type)) {
            $query = $query
                ->andWhere('s.categorie = :type')
                ->setParameter('type', "$type");
        }

        if (!is_null($search->q)) {
            $query->andWhere('s.numero LIKE :q or s.batiment LIKE :q or s.etage LIKE :q')
                ->setParameter('q',"%{$search->q}%");
        }

        if (!empty($search->centres)) {
            $query->andWhere('c.id LIKE :centres')
                ->setParameter('centres',$search->centres);
        }

        if (!empty($search->dispoLe)) {
            $query->leftJoin('s.empruntSalles', 'e', 'WITH', 'not((e.dateDebut > :dl AND e.dateFin > :dl) OR (e.dateDebut < :dl AND e.dateFin < :dl))')
                ->andWhere('e.libelle is null')
                ->setParameter('dl', $search->dispoLe);
        }elseif (!empty($search->dispoMin) and !empty($search->dispoMax)) {
            $query->leftJoin('s.empruntSalles', 'e', 'WITH', 'not((e.dateDebut > :da AND e.dateFin > :da) OR (e.dateDebut < :db AND e.dateFin < :db))')
                ->andWhere('e.libelle is null')
                ->setParameter('db', $search->dispoMin)
                ->setParameter('da', $search->dispoMax);
        } elseif (!empty($search->dispoNow)) {
            if ( $search->dispoNow === true) {
                $query->leftJoin('s.empruntSalles', 'e', 'WITH', 'not((e.dateDebut > :dn AND e.dateFin > :dn) OR (e.dateDebut < :dn AND e.dateFin < :dn))')
                    ->andWhere('e.libelle is null')
                    ->setParameter('dn', new \DateTime());
            }
        }

        // Execute the query
        return $query->getQuery()->getResult();
    }
}
