<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CategoryRepository extends NestedTreeRepository implements ServiceEntityRepositoryInterface
{
    public function __construct(EntityManagerInterface $manager)
    {
        $entityClass = Category::class;
//        $manager = $registry->getManagerForClass($entityClass);
        parent::__construct($manager, $manager->getClassMetadata($entityClass));
    }
}