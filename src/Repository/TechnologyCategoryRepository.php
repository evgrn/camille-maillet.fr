<?php

namespace App\Repository;

use App\Entity\TechnologyCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TechnologyCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method TechnologyCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method TechnologyCategory[]    findAll()
 * @method TechnologyCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TechnologyCategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TechnologyCategory::class);
    }

}
