<?php

namespace App\Repository;

use App\Entity\Auto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Auto>
 *
 * @method Auto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Auto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Auto[]    findAll()
 * @method Auto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AutoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Auto::class);
    }

    public function add(Auto $auto, bool $flush = false): void
    {
        $this->getEntityManager()->persist($auto);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Auto $auto, bool $flush = false): void
    {
        $this->getEntityManager()->remove($auto);

        if ($flush) {
            try {
                $this->getEntityManager()->flush();
            } catch (\Exception $e) {
            
            }
        }
    }

    public function findByModele() : array {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('v')
        ->from(Auto::class,'v')
        ->groupBy('v.Modele');
        return $qb->getQuery()->getResult();
    }

    public function findByMarque() : array {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('v.Marque')
        ->from(Auto::class,'v')
        ->groupBy('v.Marque');
        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Auto[] Returns an array of Auto objects
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

//    public function findOneBySomeField($value): ?Auto
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
