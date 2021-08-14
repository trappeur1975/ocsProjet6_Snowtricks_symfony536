<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($nbrTrick = 1; $nbrTrick <= 10; $nbrTrick++) {

            // we retrieve the reference of the pool and the user
            $pool = $this->getReference('pool' . rand(1, 4));
            $user = $this->getReference('user' . rand(1, 4));

            $trick = new Trick();
            $trick->setName($faker->sentence())
                ->setDescription($faker->paragraph())
                ->setPool($pool)
                ->setUser($user);

            $manager->persist($trick);

            // we save the reference of the trick
            $this->addReference('trick' . $nbrTrick, $trick);
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
