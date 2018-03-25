<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * @param $processed
     * @return mixed
     *
     * Affiche la totalité des messages dont l'attribut $processed vaut true.
     */
    public function findAllByProcessed($processed)
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.date', 'DESC')
            ->where('m.processed = :processed')
            ->setParameter('processed', $processed)
            ->getQuery()
            ->getResult()
            ;

    }

    /**
     * @param $limit
     * @return mixed
     *
     * Affiche les derniers messages dont l'attribut $processed vaut true
     * et fixe la valeur $limit entrée en paramètre comme limite.
     */
    public function findLastUnprocessed($limit)
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.date', 'DESC')
            ->where('m.processed = :processed')
            ->setParameter('processed', false)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;

    }

    /**
     * @return mixed
     * @throws NonUniqueResultException
     *
     * Récupère le nombre d'entités Message dont l'attribut $processed vaut false.
     */
    public function countUnprocessed()
    {
        return $this->createQueryBuilder('m')
            ->select('count(m.id)')
            ->where('m.processed = :processed')
            ->setParameter('processed', false)
            ->getQuery()
            ->getSingleScalarResult();
    }



}
