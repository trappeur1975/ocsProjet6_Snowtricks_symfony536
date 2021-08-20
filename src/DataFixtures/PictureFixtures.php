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

        // $nomImg = basename($img);
        // $nomImg = str_replace('public/pictures/', '', $img);

        for ($nbrPicture = 1; $nbrPicture <= 10; $nbrPicture++) {

            // we retrieve the reference of the trick
            $trick = $this->getReference('trick' . rand(1, 10));

            //Obtenir l'image :
            $imgUrl = $faker->imageUrl();
            //Definir le nom de l'image
            $imgName = "picture$nbrPicture.png";
            //Définir le chemin et le nom du fichier
            $imgPath = "./public/pictures/$imgName";

            //Enregistrer
            file_put_contents($imgPath, file_get_contents($imgUrl));

            $picture = new Picture();

            $picture->setPictureFileName($imgName);

            // $picture->setPictureFileName($img);
            // $picture->setPictureFileName($nomImg);

            // $picture->setPictureFileName($faker->imageUrl());

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
