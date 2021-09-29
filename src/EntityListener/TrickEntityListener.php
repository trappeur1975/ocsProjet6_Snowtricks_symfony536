<?php

namespace App\EntityListener;

use App\Entity\Trick;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickEntityListener
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Trick $trick, LifecycleEventArgs $event)
    {
        $trick->computeSlug($this->slugger);
    }

    public function preUpdate(Trick $trick, LifecycleEventArgs $event)
    {
        $trick->computeSlug($this->slugger);
    }
}
