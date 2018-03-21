<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

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



}
