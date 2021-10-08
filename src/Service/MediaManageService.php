<?php

namespace App\Service;

use App\Entity\Picture;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MediaManageService
{

    /**
     * params
     * @var ParameterBagInterface $params allows access to the parameters defined in the config> service.yaml file 
     */
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     * Method sourceVideo retrieve the source of the video (end of the string (youtube url) from the last "/" or the last "=") 
     *
     * @param String $srcVideo video url
     *
     * @return string
     */
    public function sourceVideo(String $srcVideo): ?string
    {
        if (strripos($srcVideo, "=") == true) {
            return substr(strrchr($srcVideo, "="), 1);
        } else {
            return substr(strrchr($srcVideo, "/"), 1);
        }
    }

    /**
     * Method addImageOnServer addition (physically) of an uploader image on the server et return a Picture 
     *
     * @param UploadedFile $pictureUpload image file that was uploaded 
     *
     * @return Picture
     */
    public function addImageOnServer(UploadedFile $pictureUpload)
    {
        //We generate a new picture file name
        $pictureFileName = uniqid() . '.' . $pictureUpload->guessExtension();

        // We copy the file to the picture folder
        $pictureUpload->move(
            $this->params->get('pictures_directory_contributions'),
            $pictureFileName
        );

        // we create a Picture entity which will be saved after its return to the database
        $newPicture = new Picture();
        $newPicture->setPictureFileName($pictureFileName);
        $newPicture->setAlt(pathinfo($pictureUpload->getClientOriginalName(), PATHINFO_BASENAME));

        // return  $pictureFileName;
        return  $newPicture;
    }
}
