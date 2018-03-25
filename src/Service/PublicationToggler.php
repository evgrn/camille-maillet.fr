<?php


namespace App\Service;
use Doctrine\ORM\EntityManager;


/**
 * Class PublicationToggler
 * @package App\Service
 *
 * Service permettant de changer l'attribut $published d'une entité dynamiquement.
 */
class PublicationToggler
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * PublicationToggler constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em){
        $this->em = $em;
    }


    /**
     * @param $entity
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * Change dynamiquement l'attribut $publishede l'entité $entity entrée en parmètre
     * et retourne le mesage correspondant à sa nouvelle valeur.
     */
    public function toggle($entity){
        $newStatus = $entity->getPublished() ? false : true;
        $entity->setPublished($newStatus);
        $this->em->flush();
        return $entity->getPublished() ? "L'élément a été publié" : "L'élément a été dépublié";
    }

}