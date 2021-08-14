<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class PictureFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($nbrPicture = 1; $nbrPicture <= 10; $nbrPicture++) {

            // we retrieve the reference of the trick
            $trick = $this->getReference('trick' . rand(1, 10));

            $picture = new Picture();
            $picture->setPictureFileName($faker->imageUrl());
            $picture->setTrick($trick);
            $manager->persist($picture);
        }

        $manager->flush();
    }

    // returns the list of our fixture dependencies for this fixture
    public function getDependencies()
    {
        return [
            TrickFixtures::class
        ];
    }
}
