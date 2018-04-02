<?php

namespace App\Repository;

use App\Entity\Bio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Bio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bio[]    findAll()
 * @method Bio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Bio::class);
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getBio(){
        return $this->createQueryBuilder('b')
            ->where('b.id = 1')
            ->getQuery()
            ->getOneOrNullResult();
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('b')
            ->where('b.something = :value')->setParameter('value', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
