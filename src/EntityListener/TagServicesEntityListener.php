<?php

namespace App\EntityListener;

use App\Entity\TagServices;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class TagServicesEntityListener
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(TagServices $tag, LifecycleEventArgs $event)
    {
        $em = $event->getEntityManager();
        $tag->computeSlug($this->slugger, $em);
    }

    public function preUpdate(TagServices $tag, LifecycleEventArgs $event)
    {
        $em = $event->getEntityManager();
        $tag->computeSlug($this->slugger, $em);
    }
}