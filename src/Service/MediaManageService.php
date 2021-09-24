<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MediaManageService
{

    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    // retrieve the source of the video (end of the string (youtube url) from the last "/" or the last "=") 
    public function sourceVideo(String $srcVideo): ?string
    {
        if (strripos($srcVideo, "=") == true) {
            return substr(strrchr($srcVideo, "="), 1);
        } else {
            return substr(strrchr($srcVideo, "/"), 1);
        }
    }

    // addition (physically) of an uploader image on the server
    public function addImageOnServer(UploadedFile $pictureUpload)
    {
        //We generate a new picture file name
        $pictureFileName = uniqid() . '.' . $pictureUpload->guessExtension();

        // We copy the file to the picture folder
        $pictureUpload->move(
            $this->params->get('pictures_directory'),
            $pictureFileName
        );

        return  $pictureFileName;
    }
}
