<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Message;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($nbrMessage = 1; $nbrMessage <= 20; $nbrMessage++) {

            // we retrieve the reference of the pool and the user
            $trick = $this->getReference('trick' . rand(1, 10));
            $user = $this->getReference('user' . rand(1, 4));

            $message = new Message();
            $message->setContent($faker->text())
                ->setCreateAt($faker->dateTimeBetween('-6 month', 'now'))
                ->setTrick($trick)
                ->setUser($user);

            $manager->persist($message);

            // we save the reference of the trick
            $this->addReference('message' . $nbrMessage, $message);
        }

        $manager->flush();
    }

    // returns the list of our fixture dependencies for this fixture
    public function getDependencies()
    {
        return [
            TrickFixtures::class,
            UserFixtures::class
        ];
    }
}
