<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Picture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class PictureFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        // $img = $faker->image('public/pictures/');

        // $img = $faker->image('public/pictures\\', 640, 480, 'animals');
        // $img = $faker->image('public/pictures/');

        // $nomImg = basename($img);
        // $nomImg = str_replace('public/pictures/', '', $img);

        for ($nbrPicture = 1; $nbrPicture <= 10; $nbrPicture++) {

            // we retrieve the reference of the trick
            $trick = $this->getReference('trick' . rand(1, 10));

            $picture = new Picture();
            $picture->setPictureFileName($faker->imageUrl());

            // $picture->setPictureFileName($img);
            // $picture->setPictureFileName($nomImg);

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
