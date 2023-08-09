<?php

namespace App\Repository;

use App\Entity\Park;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Park>
 *
 * @method Park|null find($id, $lockMode = null, $lockVersion = null)
 * @method Park|null findOneBy(array $criteria, array $orderBy = null)
 * @method Park[]    findAll()
 * @method Park[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Park::class);
    }

    public function add(Park $park, bool $flush = false): void
    {
        $this->getEntityManager()->persist($park);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Park $park, bool $flush = false): void
    {
        $this->getEntityManager()->remove($park);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Park[] Returns an array of Park objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Park
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
