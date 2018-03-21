<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Production;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductionCategoryRepository")
 */
class ProductionCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Production", mappedBy="productionCategory")
     */
    private $productions;


    public function getPropertyArray(){
        return array(
            'name' => $this->getName(),
            'id' =>$this->getId()
        );
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }



    /**
     * @return mixed
     */
    public function getProductions()
    {
        return $this->productions;
    }

    /**
     * @param mixed $productions
     */
    public function setProductions($productions): void
    {
        $this->productions = $productions;
    }

    /**
     * @param Production $production
     */
    public function addProduction(Production $production)
    {
        $this->productions[] = $production;

        $production->setProductionCategory($this);
    }

    /**
     * @param Production $application
     */
    public function removeProduction(Production $production)
    {
        $this->productions->removeElement($production);
    }
}
