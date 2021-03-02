<?php

namespace App\EntityListener;

use App\Entity\Product;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductEntityListener
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Product $product, LifecycleEventArgs $event)
    {
        $em = $event->getEntityManager();
        $product->computeSlug($this->slugger, $em);
    }

    public function preUpdate(Product $product, LifecycleEventArgs $event)
    {
        $em = $event->getEntityManager();
        $product->computeSlug($this->slugger, $em);
    }
}