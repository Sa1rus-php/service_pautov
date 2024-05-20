<?php

namespace App\Repository;

use App\Entity\SubProducts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SubProducts>
 *
 * @method SubProducts|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubProducts|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubProducts[]    findAll()
 * @method SubProducts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubProducts::class);
    }

//    /**
//     * @return SubProducts[] Returns an array of SubProducts objects
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

//    public function findOneBySomeField($value): ?SubProducts
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
