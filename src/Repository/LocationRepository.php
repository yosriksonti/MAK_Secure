<?php

namespace App\Repository;

use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Location>
 *
 * @method Location|null find($id, $lockMode = null, $lockVersion = null)
 * @method Location|null findOneBy(array $criteria, array $orderBy = null)
 * @method Location[]    findAll()
 * @method Location[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Location::class);
    }

    public function add(Location $location, bool $flush = false): Location
    {
        $this->getEntityManager()->persist($location);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
        return $location;
    }

    public function remove(Location $location, bool $flush = false): void
    {
        $this->getEntityManager()->remove($location);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByModele(String $modele) : array {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT l, v
            FROM App\Entity\Location l
            INNER JOIN l.Vehicule v
            WHERE v.Modele = :modele'
        )->setParameter('modele', $modele);

        return $query->getResult();
    }

//    /**
//     * @return Location[] Returns an array of Location objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Location
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
