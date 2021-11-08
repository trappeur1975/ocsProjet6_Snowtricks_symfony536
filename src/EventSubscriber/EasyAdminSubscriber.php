<?php

# src/EventSubscriber/EasyAdminSubscriber.php
namespace App\EventSubscriber;

use App\Entity\Trick;
use App\Entity\Video;
use App\Entity\Picture;
use App\Service\MediaManageService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
// use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $media;

    public function __construct(MediaManageService $media)
    {
        $this->media = $media;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setVideoUrlNew'],
            BeforeEntityUpdatedEvent::class => ['setVideoUrlUpdate'],
            // BeforeEntityDeletedEvent::class => ['deleteVideoUpdate']
        ];
    }

    // for adding a new video 
    public function setVideoUrlNew(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        // if (!($entity instanceof Video)) {
        //     return;
        // }

        // if we use the form admin > video 
        if ($entity instanceof Video) {
            $sourceVideoFileName = $entity->getVideoFileName();

            $videoFileName = $this->media->sourceVideo($sourceVideoFileName);

            $entity->setVideoFileName($videoFileName);
        }

        // if we use the form admin > tricks 
        if ($entity instanceof Trick) {

            $videos = $entity->getVideos();

            foreach ($videos as $video) {
                $sourceVideoFileName = $video->getVideoFileName();

                $videoFileName = $this->media->sourceVideo($sourceVideoFileName);

                $video->setVideoFileName($videoFileName);

                $entity->addVideo($video);
            }
        }
    }

    // for the video update 
    public function setVideoUrlUpdate(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        // if we use the form admin > video 
        if ($entity instanceof Video) {

            $sourceVideoFileName = $entity->getVideoFileName();

            if (strpos($sourceVideoFileName, '=') === false) { //to manage the case where the source field (url) of the video is not modified
                $entity->setVideoFileName($sourceVideoFileName);
            } else {
                $videoFileName = $this->media->sourceVideo($sourceVideoFileName);

                $entity->setVideoFileName($videoFileName);
            }
        }

        // if we use the form admin > tricks
        if ($entity instanceof Trick) {

            $videos = $entity->getVideos();

            foreach ($videos as $video) {
                $sourceVideoFileName = $video->getVideoFileName();
                // $video->setTrick($entity);

                if (strpos($sourceVideoFileName, '=') === false) { //to manage the case where the source field (url) of the video is not modified
                    $entity->addVideo($video);
                } else {
                    $videoFileName = $this->media->sourceVideo($sourceVideoFileName);

                    $video->setVideoFileName($videoFileName);
                }

                $entity->addVideo($video);
            }
        }
    }

    // public function deleteVideoUpdate(BeforeEntityDeletedEvent $event)
    // {
    //     $entity = $event->getEntityInstance();

    //     dd($entity);

    //     // if we use the form admin > tricks
    //     if ($entity instanceof Trick) {
    //     }
    // }
}
