<?php

namespace App\Utils;

class ImageUpload
{

    public function save($image, $name, $dir){

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