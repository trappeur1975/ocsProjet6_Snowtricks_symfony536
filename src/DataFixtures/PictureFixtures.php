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

        // [picture][trick]
        $tabsPictureTrick = [
            ['trick1.jpg' => '1'],
            [
                'trick2_1.jpg' => '2',
                'trick2_2.jpg' => '2',
                'trick2_3.jpg' => '2',
                'trick2_4.jpg' => '2'
            ],
            ['trick3.jpg' => '3'],
            ['trick4.jpg' => '4'],
            [
                'trick5_1.jpg' => '5',
                'trick5_2.jpg' => '5',
                'trick5_3.jpg' => '5'
            ],
            ['trick6.jpg' => '6'],
            ['trick7.jpg' => '7'],
            ['trick8.jpg' => '8'],
            [
                'trick9_1.jpg' => '9',
                'trick9_2.jpg' => '9',
                'trick9_3.jpg' => '9'
            ],
            ['trick10.jpg' => '10']
        ];

        $countPicture = 1;

        foreach ($tabsPictureTrick as $tabs) {
            foreach ($tabs as $picture => $trick) {
                // we retrieve the reference of the trick
                $trick = $this->getReference('trick' . $trick);

                //Get the picture
                $imgUrl = "./public/pictures/datafixture/$picture";
                //Define the name of the image 
                $imgName = "picture$countPicture.jpg";
                //Define the path and name of the file 
                $imgPath = "./public/pictures/contributions/$imgName";

                //Save
                file_put_contents($imgPath, file_get_contents($imgUrl));

                $picture = new Picture();

                $picture->setPictureFileName($imgName);
                $picture->setAlt('image' . $countPicture);

                $picture->setTrick($trick);
                $manager->persist($picture);

                $countPicture++;
            }
        }

        $manager->flush();

        // $faker = Factory::create('fr_FR');

        // for ($nbrPicture = 1; $nbrPicture <= 10; $nbrPicture++) {

        //     // we retrieve the reference of the trick
        //     $trick = $this->getReference('trick' . rand(1, 10));

        //     //Get the picture
        //     $imgUrl = $faker->imageUrl();
        //     //Define the name of the image 
        //     $imgName = "picture$nbrPicture.png";
        //     //Define the path and name of the file 
        //     $imgPath = "./public/pictures/$imgName";

        //     //Save
        //     file_put_contents($imgPath, file_get_contents($imgUrl));

        //     $picture = new Picture();

        //     $picture->setPictureFileName($imgName);
        //     $picture->setAlt('image' . $nbrPicture);

        //     $picture->setTrick($trick);
        //     $manager->persist($picture);
        // }

        // $manager->flush();
    }

    // returns the list of our fixture dependencies for this fixture
    public function getDependencies()
    {
        return [
            TrickFixtures::class
        ];
    }
}
