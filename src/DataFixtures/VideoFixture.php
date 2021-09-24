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
            'https://youtu.be/2WQzBNlZco4',
            'https://youtu.be/CcUjwt0M31I',
            'https://youtu.be/xAnZwxfI91Q',
            'https://youtu.be/3LQYUBw_Icc',
            'https://youtu.be/W3PBK2hJLnY',
            'https://youtu.be/98igPe8mv8A',
            'https://youtu.be/AKCbty2AmO0',
            'https://youtu.be/sSUnnAr9JGA',
            'https://youtu.be/2yiTxPj0Jo0',
            'https://youtu.be/2sSI1esY4JI'
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
