<?php

namespace App\Repository;

use App\Entity\Vehicule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Vehicule::class);
    }

    public function add(Vehicule $vehicule, bool $flush = false): void
    {
        $this->getEntityManager()->persist($vehicule);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Vehicule $vehicule, bool $flush = false): void
    {
        $this->getEntityManager()->remove($vehicule);

        if ($flush) {
            try {
                $this->getEntityManager()->flush();
            } catch (\Exception $e) {
            
            }
        }
    }

    public function findByModeleDispo(bool $dispo) : array {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $str = $dispo?'1':'0';
        $qb->select('v')
        ->from(Vehicule::class,'v')
        ->andWhere('v.Dispo = '.$str)
        ->groupBy('v.Modele');
        return $qb->getQuery()->getResult();
    }
    public function findByModele() : array {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('v')
        ->from(Vehicule::class,'v')
        ->groupBy('v.Modele');
        return $qb->getQuery()->getResult();
    }

    public function findByMarque() : array {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('v.Marque')
        ->from(Vehicule::class,'v')
        ->andWhere('v.Dispo = 1')
        ->groupBy('v.Marque');
        return $qb->getQuery()->getResult();
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
}
