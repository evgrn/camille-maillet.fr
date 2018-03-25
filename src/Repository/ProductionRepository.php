<?php

namespace App\Repository;

use App\Entity\Production;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Production|null find($id, $lockMode = null, $lockVersion = null)
 * @method Production|null findOneBy(array $criteria, array $orderBy = null)
 * @method Production[]    findAll()
 * @method Production[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Production::class);
    }


    /**
     * @return mixed
     *
     * Récupération de la totalité des entités Production triés par date.
     */
    public function findAllOrderedByDate()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return mixed
     *
     * Récupération de la totalité des entités Production dont l'attribut $published vaut True triés par date.
     */
    public function findAllPublishedOrderedByDate()
    {
        return $this->createQueryBuilder('p')
            ->where('p.published = :published')
            ->setParameter('published', true)
            ->orderBy('p.date', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $category
     * @return mixed
     *
     * Récupération de la totalité des entités Production dont l'attribut $published vaut True
     * et dont la catégorie correspond à celle entrée en paramètre,  triés par date.
     */
    public function findAllPublishedByCategory($category){
        return $this->createQueryBuilder('p')
            ->where('p.published = :published')
            ->setParameter('published', true)
            ->andWhere('p.productionCategory = :category')
            ->setParameter('category', $category)
            ->orderBy('p.date', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

}
