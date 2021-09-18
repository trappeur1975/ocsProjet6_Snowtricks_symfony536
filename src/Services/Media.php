<?php

namespace App\Services;

class Media
{
    // retrieve the source of the video (end of the string (youtube url) from the last "/" or the last "=") 
    public function sourceVideo(String $srcVideo): ?string
    {
        if (strripos($srcVideo, "=") == true) {
            return substr(strrchr($srcVideo, "="), 1);
        } else {
            return substr(strrchr($srcVideo, "/"), 1);
        }
    }
}
