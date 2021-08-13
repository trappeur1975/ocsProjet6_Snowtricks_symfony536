<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($nbrTrick = 1; $nbrTrick <= 10; $nbrTrick++) {

            // we retrieve the reference of the pool and the user
            $pool = $this->getReference('pool' . rand(1, 4));
            $user = $this->getReference('user' . rand(1, 4));

            $trick = new Trick();
            $trick->setName('trick n°' . $nbrTrick)
                ->setDescription('<p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Vero dolore perferendis natus rem, libero laudantium temporibus beatae quae voluptatibus omnis totam maxime consequatur quod sapiente voluptatem, non ipsum quam placeat.</p>')
                ->setPool($pool)
                ->setUser($user);
            $manager->persist($trick);
        }

        $manager->flush();
    }

    // returns the list of our fixture dependencies for this fixture
    public function getDependencies()
    {
        return [
            PoolFixtures::class,
            UserFixtures::class
        ];
    }
}
