<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{

    public function upload(UploadedFile $file, string $directory, string $name = "default") : string{

        if($file){
            $newFileName = $name . "-" . uniqid() . "." .$file->guessExtension() ;
            $file->move($directory, $newFileName);

            return $newFileName;
        }

        return "";
    }

}