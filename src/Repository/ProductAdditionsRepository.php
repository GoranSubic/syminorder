<?php

namespace App\Repository;

use App\Entity\ProductAdditions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductAdditions|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductAdditions|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductAdditions[]    findAll()
 * @method ProductAdditions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductAdditionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductAdditions::class);
    }

    // /**
    //  * @return ProductAdditions[] Returns an array of ProductAdditions objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProductAdditions
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
