<?php

namespace App\Repository;

use App\Entity\Technology;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Technology|null find($id, $lockMode = null, $lockVersion = null)
 * @method Technology|null findOneBy(array $criteria, array $orderBy = null)
 * @method Technology[]    findAll()
 * @method Technology[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TechnologyRepository extends ServiceEntityRepository
{
    /**
     * TechnologyRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Technology::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     *
     * Récupération des entités Technology dont les propriétés $mastered et $published valent true.
     */
    public function getAllMasteredAndPublished()
    {
        return $this->createQueryBuilder('t')
            ->where('t.mastered = :mastered')->setParameter('mastered', true)
            ->andWhere('t.published = :published')->setParameter('published', true)

        ;
    }

    /**
     * @param $mastered
     * @return mixed
     *
     * Récupération des entités Technology publiées selon la valeur de leur attribut $mastered entrée en paramètre.
     */
    public function getByMasteredAndPublished($mastered)
    {
        return $this->createQueryBuilder('t')
            ->where('t.mastered = :mastered')->setParameter('mastered', $mastered)
            ->andWhere('t.published = :published')->setParameter('published', true)
            ->getQuery()
            ->getResult()
            ;
    }

}
