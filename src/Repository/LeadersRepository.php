<?php

namespace App\Repository;

use App\Entity\Leaders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Leaders|null find($id, $lockMode = null, $lockVersion = null)
 * @method Leaders|null findOneBy(array $criteria, array $orderBy = null)
 * @method Leaders[]    findAll()
 * @method Leaders[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeadersRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Leaders::class);
    }

//    /**
//     * @return Leaders[] Returns an array of Leaders objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Leaders
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
