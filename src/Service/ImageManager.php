<?php


namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;


class ImageManager
{
    private $targetDirectory;

    public function __construct($targetDirectory){
        $this->targetDirectory = $targetDirectory;
    }


    public function manageImageUpdate($currentImage, $originalImage){

        if($currentImage != NULL ){
            $this->delete($originalImage);
            return $this->uploadImage($currentImage);
        }
        else{
            return $originalImage;
        }
    }


    public function uploadImage(UploadedFile $file){
        $fileName = $this->generateFileName($file);
        $file->move($this->getTargetDirectory(), $fileName);
        return $fileName;
    }

    public function delete($image){
        unlink($this->getTargetDirectory() .'/' . $image);
    }

    public function generateFileName($file){
        return md5(uniqid()) . '.' . $file->guessExtension();
    }

    public function getTargetDirectory(){
        return $this->targetDirectory;
    }
}