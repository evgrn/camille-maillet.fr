<?php


namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory){
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file){
        $fileName = $this->generateFileName($file);
        $file->move($this->getTargetDirectory(), $fileName);
        return $fileName;
    }

    public function generateFileName($file){
        return md5(uniqid()) . '.' . $file->guessExtension();
    }

    public function getTargetDirectory(){
        return $this->targetDirectory;
    }
}