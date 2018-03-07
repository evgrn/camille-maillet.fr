<?php

namespace App\Repository;

use App\Entity\Irl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Irl|null find($id, $lockMode = null, $lockVersion = null)
 * @method Irl|null findOneBy(array $criteria, array $orderBy = null)
 * @method Irl[]    findAll()
 * @method Irl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IrlRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Irl::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('i')
            ->where('i.something = :value')->setParameter('value', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
