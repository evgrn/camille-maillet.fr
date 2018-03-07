<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Entity\Technology;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductionRepository")
 */
class Production
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
     private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Assert\Url
     */
    private $url;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\Image
     */
    private $thumbnail;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\Image
     */
    private $image;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var array
     * @ORM\ManyToMany(targetEntity="App\Entity\Technology", inversedBy="productions")
     */
    private $technologies;

    /**
     * @var \App\Entity\ProductionCategory
     * @ORM\ManyToOne(targetEntity="App\Entity\ProductionCategory", inversedBy="productions")
     */
    private $productionCategory;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $date;
    /**
     * @ORM\Column(type="boolean")
     */
    private $published;

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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @param string $thumbnail
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return array
     */
    public function getTechnologies()
    {
        return $this->technologies;
    }

    /**
     * @param array $technologies
     */
    public function setTechnologies(array $technologies): void
    {
        $this->technologies = $technologies;
    }

    /**
     * @return ProductionCategory
     */
    public function getProductionCategory()
    {
        return $this->productionCategory;
    }

    /**
     * @param ProductionCategory $productionCategory
     */
    public function setProductionCategory(ProductionCategory $productionCategory): void
    {
        $this->productionCategory = $productionCategory;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function addTechnology(Technology $technology)
    {
        $this->technologies[] = $technology;

        $technology->addProduction($this);

        return $this;
    }

    public function removeTechnology(Technology $technology)
    {
        $this->technologies->removeElement($technology);
        $technology->removeElement($this);

    }









}
