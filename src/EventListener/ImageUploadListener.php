<?php


namespace App\EventListener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Entity\Technology;
use App\Service\ImageUploader;

class ImageUploadListener
{
    private $uploader;

    public function __construct(ImageUploader $uploader){
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args){
        $entity = $args->getEntity();
        $this->uploadFile($entity);
    }

    public function preUpdate(LifecycleEventArgs $args){
        $entity = $args->getEntity();
        $this->uploadFile($entity);
    }

    public function uploadFile($entity){
        if(!$entity instanceof Technology){
            return;
        }

        $file = $entity->getImage();

        if($file instanceof UploadedFile){
            $fileName = $this->uploader->upload($file);
            $entity->setBrochure($fileName);
        }

    }

}