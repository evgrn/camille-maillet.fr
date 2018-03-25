<?php


namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ImageManager
 * @package App\Service
 *
 * Service gérant le stockage et la suppression des images envoyées sur le serveur par un formulaire.
 */
class ImageManager
{
    /**
     * @var
     * Adresse du dossier cible
     */
    private $targetDirectory;

    /**
     * ImageManager constructor.
     * @param $targetDirectory
     */
    public function __construct($targetDirectory){
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * @param $currentImage
     * @param $originalImage
     * @return string
     *
     * Vérifie si l'utilisateur a envoyé une nouvelle image.
     * Si oui, supprime le nom et le fichier de l'image actuelle, la remplace par ceux de la nouvelle et retourne le nom de la nouvelle image.
     * Sinon retourne le nom de l'image originale.
     */
    public function manageImageUpdate($currentImage, $originalImage){

        if($currentImage != NULL ){
            $this->delete($originalImage);
            return $this->uploadImage($currentImage);
        }
        else{
            return $originalImage;
        }
    }

    /**
     * @param UploadedFile $file
     * @return string
     *
     * Stocke l'image entrée en paramètre sur le serveur, génère et  retourne son nom unique.
     */
    public function uploadImage(UploadedFile $file){
        $fileName = $this->generateFileName($file);
        $file->move($this->getTargetDirectory(), $fileName);
        return $fileName;
    }

    /**
     * @param $image
     *
     * Supprime du serveur l'image entrée en parmètre.
     */
    public function delete($image){
        unlink($this->getTargetDirectory() .'/' . $image);
    }

    /**
     * @param $file
     * @return string
     *
     * Génère le nom unique du fichier entrée en parmètre.
     */
    public function generateFileName($file){
        return md5(uniqid()) . '.' . $file->guessExtension();
    }

    /**
     * @return mixed
     *
     * Retourne le dossier de stockage des images
     */
    public function getTargetDirectory(){
        return $this->targetDirectory;
    }
}