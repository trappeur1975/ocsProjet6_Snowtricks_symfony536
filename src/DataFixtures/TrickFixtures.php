<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Trick;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class TrickFixtures extends Fixture implements DependentFixtureInterface
{
    protected $slug;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slug = $slugger;
    }


    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $tabsTrick = [
            [
                'name' => "trick1",
                'description' => "description 1",
                'date' => $faker->dateTimeBetween('-6 month', 'now'),
                'pool' => $this->getReference('pool1'),

            ],
            [
                'name' => "trick2",
                'description' => "description 2",
                'date' => $faker->dateTimeBetween('-6 month', 'now'),
                'pool' => $this->getReference('pool2'),

            ],
            [
                'name' => "trick3",
                'description' => "description 3",
                'date' => $faker->dateTimeBetween('-6 month', 'now'),
                'pool' => $this->getReference('pool2'),

            ],
            [
                'name' => "trick4",
                'description' => "description 4",
                'date' => $faker->dateTimeBetween('-6 month', 'now'),
                'pool' => $this->getReference('pool2'),

            ],
            [
                'name' => "trick5",
                'description' => "description 5",
                'date' => $faker->dateTimeBetween('-6 month', 'now'),
                'pool' => $this->getReference('pool2'),

            ],
            [
                'name' => "trick6",
                'description' => "description 6",
                'date' => $faker->dateTimeBetween('-6 month', 'now'),
                'pool' => $this->getReference('pool2'),

            ],
            [
                'name' => "trick7",
                'description' => "description 7",
                'date' => $faker->dateTimeBetween('-6 month', 'now'),
                'pool' => $this->getReference('pool2'),

            ],
            [
                'name' => "trick8",
                'description' => "description 8",
                'date' => $faker->dateTimeBetween('-6 month', 'now'),
                'pool' => $this->getReference('pool2'),

            ],
            [
                'name' => "trick9",
                'description' => "description 9",
                'date' => $faker->dateTimeBetween('-6 month', 'now'),
                'pool' => $this->getReference('pool2'),

            ],
            [
                'name' => "trick10",
                'description' => "description 10",
                'date' => $faker->dateTimeBetween('-6 month', 'now'),
                'pool' => $this->getReference('pool2'),

            ]
        ];

        $max = count($tabsTrick);

        for ($i = 0; $i < $max; $i++) {

            $counter = $i + 1;

            // we retrieve the reference of the pool and the user
            $user = $this->getReference('user' . rand(1, 4));

            $trick = new Trick();
            $trick->setName($tabsTrick[$i]['name'])
                ->setDescription($tabsTrick[$i]['description'])
                ->setCreateAt($tabsTrick[$i]['date'])
                ->setPool($tabsTrick[$i]['pool'])
                ->setUser($user)
                ->setSlug(strtolower($this->slug->slug($trick->getName())));

            $manager->persist($trick);

            // we save the reference of the trick
            $this->addReference('trick' . $counter, $trick);
        }

        $manager->flush();

        // $faker = Factory::create('fr_FR');

        // for ($nbrTrick = 1; $nbrTrick <= 10; $nbrTrick++) {

        //     // we retrieve the reference of the pool and the user
        //     $pool = $this->getReference('pool' . rand(1, 4));
        //     $user = $this->getReference('user' . rand(1, 4));

        //     $trick = new Trick();
        //     $trick->setName($faker->sentence())
        //         ->setDescription($faker->paragraph())
        //         ->setCreateAt($faker->dateTimeBetween('-6 month', 'now'))
        //         ->setPool($pool)
        //         ->setUser($user)
        //         ->setSlug(strtolower($this->slug->slug($trick->getName())));

        //     $manager->persist($trick);

        //     // we save the reference of the trick
        //     $this->addReference('trick' . $nbrTrick, $trick);
        // }

        // $manager->flush();
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
