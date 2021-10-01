<?php

namespace App\DataFixtures;

use App\Entity\Video;
use App\Service\MediaManageService;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class VideoFixture extends Fixture implements DependentFixtureInterface
{
    // ----------to integrate and use in our fixture the "media" service that we created------
    private $media;

    public function __construct(MediaManageService $media)
    {
        $this->media = $media;
    }
    // ----------------

    public function load(ObjectManager $manager)
    {

        $videosYoutubes = [
            'https://youtu.be/SDdfIqJLrq4',
            'https://youtu.be/-FTl5BAvwWo',
            'https://youtu.be/KVXJ2E41_xE',
            'https://youtu.be/axNnKy-jfWw',
            'https://youtu.be/0uGETVnkujA',
            'https://youtu.be/_OMar04NRZw',
            'https://youtu.be/T1zEBh5HLH8',
            'https://youtu.be/R2Cp1RumorU',
            'https://youtu.be/FuZc3fTmUnc',
            'https://youtu.be/mtkXQHrMODk'
        ];

        for ($nbrVideo = 1; $nbrVideo <= 20; $nbrVideo++) {
            // we retrieve the reference of the trick
            $trick = $this->getReference('trick' . rand(1, 10));

            // we select a video randomly 
            $video = $videosYoutubes[rand(0, 9)];

            // we recover the identity of the youtube video (end of the string (youtube url))
            $videoFileName = $this->media->sourceVideo($video);

            // We create the picture in the database
            $video = new Video();
            $video->setVideoFileName($videoFileName);
            $video->setTrick($trick);

            $manager->persist($video);
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
