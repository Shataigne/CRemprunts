<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Categorie;
use App\Entity\Materiel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Materiel>
 *
 * @method Materiel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Materiel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Materiel[]    findAll()
 * @method Materiel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Materiel::class);
    }


    public function findByCategorie($value): array
    {
        return $this->createQueryBuilder('m')
            ->select('m','c')
            ->leftjoin('m.categorie', 'c')
            ->andWhere('c.libelle = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }


    //    /**
    //     * @return Materiel[] Returns an array of Materiel objects
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

    //    public function findOneBySomeField($value): ?Materiel
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findByFilters(SearchData $search, String $type)
    {
        $query = $this
            ->createQueryBuilder('m')
            ->select('c','ca','e','m')
            ->leftjoin('m.centre', 'c')
            ->leftjoin('m.categorie', 'ca')
            ->leftJoin('m.empruntMateriels','e')
            ->andWhere('ca.libelle = :type')
            ->setParameter('type',"$type");

        if (!is_null($search->q)) {
            $query->andWhere('m.libelle LIKE :q')
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
