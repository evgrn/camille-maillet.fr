<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Production;


/**
 * @ORM\Entity(repositoryClass="App\Repository\TechnologyRepository")
 *
 * Entité représentant une technologie
 */
class Technology
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
     * @ORM\Column(type="text")
     *
     */
    private $description;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\Image
     */
    private $image;

    /**
     * @ORM\Column(type="boolean")
    */
    private $mastered;


    /**
     * @ORM\Column(type="boolean")
     *
     */
    private $published;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Production", mappedBy="technologies")
     */
    private $productions;

    /**
     * @ORM\Column(type="string")
     */
    private $stack;

    /**
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * @ORM\ManyToOne(targetEntity="App\Entity\TechnologyCategory", inversedBy="technologies")
     */
    private $category;


    public function __construct(){
        $this->setPublished(false);
    }

    /**
     * @return mixed
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @param mixed $published
     */
    public function setPublished($published): void
    {
        $this->published = $published;
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
     * @param mixed $title
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $content
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {

        $this->image = $image;


        return $this;
    }

    /**
     * @return mixed
     */
    public function getMastered()
    {
        return $this->mastered;
    }

    /**
     * @param mixed $mastered
     */
    public function setMastered($mastered): void
    {
        $this->mastered = $mastered;
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

    public function addProduction(Production $production)
    {
        $this->productions[] = $production;

        return $this;
    }

    public function removeProduction(Production $production)
    {
        $this->productions->removeElement($production);

    }


    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;

    }

    /**
     * @return mixed
     */
    public function getStack()
    {
        return $this->stack;
    }

    /**
     * @param mixed $stack
     */
    public function setStack($stack): void
    {
        $this->stack = $stack;
    }


}