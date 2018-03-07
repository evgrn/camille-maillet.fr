<?php

namespace App\Repository;

use App\Entity\CommandCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CommandCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandCategory[]    findAll()
 * @method CommandCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandCategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CommandCategory::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('c')
            ->where('c.something = :value')->setParameter('value', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
