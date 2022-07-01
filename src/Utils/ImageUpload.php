<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUpload
{

    public function save(UploadedFile $image, string $name, string $dir){

        if ($image) {

            //renommer le fichier
            $newFileName = preg_replace("/\s/", "-", $name)  . "-" . uniqid() . "." . $image->guessExtension();

            //déplacer le fichier
            $image->move($dir, $newFileName);

        }else{
            $newFileName = "default.png";
        }

        return $newFileName;
    }

}