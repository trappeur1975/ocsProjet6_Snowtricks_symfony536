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

        for ($nbrPicture = 1; $nbrPicture <= 10; $nbrPicture++) {

            // we retrieve the reference of the trick
            $trick = $this->getReference('trick' . rand(1, 10));

            //Get the picture
            $imgUrl = $faker->imageUrl();
            //Define the name of the image 
            $imgName = "picture$nbrPicture.png";
            //Define the path and name of the file 
            $imgPath = "./public/pictures/$imgName";

            //Save
            file_put_contents($imgPath, file_get_contents($imgUrl));

            $picture = new Picture();

            $picture->setPictureFileName($imgName);
            $picture->setAlt('image' . $nbrPicture);

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
