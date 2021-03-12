<?php

namespace App\Repository;

use App\Entity\TagServices;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TagServices|null find($id, $lockMode = null, $lockVersion = null)
 * @method TagServices|null findOneBy(array $criteria, array $orderBy = null)
 * @method TagServices[]    findAll()
 * @method TagServices[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagServicesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TagServices::class);
    }

    // /**
    //  * @return TagServices[] Returns an array of TagServices objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TagServices
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
