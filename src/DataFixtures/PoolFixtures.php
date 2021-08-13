<?php

namespace App\DataFixtures;

use App\Entity\Pool;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class PoolFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($nbrPool = 1; $nbrPool <= 4; $nbrPool++) {
            $pool = new Pool();
            $pool->setName('pool' . $nbrPool);
            $manager->persist($pool);

            // we save the reference of the pool
            $this->addReference('pool' . $nbrPool, $pool);
        }

        $manager->flush();
    }
}
