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
                'name' => "Mute",
                'description' => "Mute : saisie de la carre frontside de la planche entre les deux pieds avec la main avant. Un grab consiste à attraper la planche avec la main pendant le saut. Le verbe anglais to grab signifie « attraper». Il existe plusieurs types de grabs selon la position de la saisie et la main choisie pour l'effectuer, avec des difficultés variables.",
                'date' => $faker->dateTimeBetween('-6 month', 'now'),
                'pool' => $this->getReference('pool1'),

            ],
            [
                'name' => "Sad",
                'description' => "Sad : saisie de la carre backside de la planche, entre les deux pieds, avec la main avant. Il existe plusieurs types de grabs selon la position de la saisie et la main choisie pour l'effectuer, avec des difficultés variables.",
                'date' => $faker->dateTimeBetween('-6 month', 'now'),
                'pool' => $this->getReference('pool1'),

            ],
            [
                'name' => "Tail grab",
                'description' => "tail grab : saisie de la partie arrière de la planche, avec la main arrière. Il existe plusieurs types de grabs selon la position de la saisie et la main choisie pour l'effectuer, avec des difficultés variables.",
                'date' => $faker->dateTimeBetween('-6 month', 'now'),
                'pool' => $this->getReference('pool1'),

            ],
            [
                'name' => "Nose grab",
                'description' => "nose grab : saisie de la partie avant de la planche, avec la main avant. Il existe plusieurs types de grabs selon la position de la saisie et la main choisie pour l'effectuer, avec des difficultés variables.",
                'date' => $faker->dateTimeBetween('-6 month', 'now'),
                'pool' => $this->getReference('pool1'),

            ],
            [
                'name' => "Front flips",
                'description' => "Un flip est une rotation verticale.Le front flips est une rotations en avant. Il est possible de faire plusieurs flips à la suite, et d'ajouter un grab à la rotation. Les flips agrémentés d'une vrille existent aussi (Mac Twist, Hakon Flip...), mais de manière beaucoup plus rare, et se confondent souvent avec certaines rotations horizontales désaxées.",
                'date' => $faker->dateTimeBetween('-6 month', 'now'),
                'pool' => $this->getReference('pool2'),

            ],
            [
                'name' => "Back flips",
                'description' => "Un flip est une rotation verticale.Le back flips est une  rotations en arrière. Il est possible de faire plusieurs flips à la suite, et d'ajouter un grab à la rotation. Les flips agrémentés d'une vrille existent aussi (Mac Twist, Hakon Flip...), mais de manière beaucoup plus rare, et se confondent souvent avec certaines rotations horizontales désaxées.",
                'date' => $faker->dateTimeBetween('-6 month', 'now'),
                'pool' => $this->getReference('pool2'),

            ],
            [
                'name' => "Nose slide",
                'description' => "Un slide consiste à glisser sur une barre de slide. Le slide se fait soit avec la planche dans l'axe de la barre, soit perpendiculaire, soit plus ou moins désaxé. On peut slider avec la planche centrée par rapport à la barre (celle-ci se situe approximativement au-dessous des pieds du rideur), mais aussi en nose slide, c'est-à-dire l'avant de la planche sur la barre.",
                'date' => $faker->dateTimeBetween('-6 month', 'now'),
                'pool' => $this->getReference('pool3'),

            ],
            [
                'name' => "Tail slide",
                'description' => "Un slide consiste à glisser sur une barre de slide. Le slide se fait soit avec la planche dans l'axe de la barre, soit perpendiculaire, soit plus ou moins désaxé. On peut slider avec la planche centrée par rapport à la barre (celle-ci se situe approximativement au-dessous des pieds du rideur), mais aussi en tail slide, l'arrière de la planche sur la barre.",
                'date' => $faker->dateTimeBetween('-6 month', 'now'),
                'pool' => $this->getReference('pool3'),

            ],
            [
                'name' => "Rotation frontside",
                'description' => "Une rotation frontside correspond à une rotation orientée vers la carre backside. Cela peut paraître incohérent mais l'origine étant que dans un halfpipe ou une rampe de skateboard, une rotation frontside se déclenche naturellement depuis une position frontside (i.e. l'appui se fait sur la carre frontside), et vice-versa. Ainsi pour un rider qui a une position regular (pied gauche devant), une rotation frontside se fait dans le sens inverse des aiguilles d'une montre.",
                'date' => $faker->dateTimeBetween('-6 month', 'now'),
                'pool' => $this->getReference('pool4'),

            ],
            [
                'name' => "Rotation Backside",
                'description' => "Les rotations backside (dos présenté en premier) nécessitent les mêmes aptitudes que pour le frontside. En revanche, l'encrage visuel va être inversé et l'appui va s'effectuer sur la pointe des pieds dans le snowpark, toujours avec la courbe en S. En conclusion, soignez vos appuis, vos phases d'armement, de déclenchement et gardez un bon encrage visuel pour de bonnes rotations !",
                'date' => $faker->dateTimeBetween('-6 month', 'now'),
                'pool' => $this->getReference('pool4'),

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
