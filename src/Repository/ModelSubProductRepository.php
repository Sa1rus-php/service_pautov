<?php

namespace App\Repository;

use App\Entity\ModelSubProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ModelSubProduct>
 *
 * @method ModelSubProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModelSubProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModelSubProduct[]    findAll()
 * @method ModelSubProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModelSubProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModelSubProduct::class);
    }

//    /**
//     * @return ModelSubProduct[] Returns an array of ModelSubProduct objects
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

//    public function findOneBySomeField($value): ?ModelSubProduct
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
