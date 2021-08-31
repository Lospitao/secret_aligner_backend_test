<?php

namespace App\Repository;

use App\Entity\TodosList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TodosList|null find($id, $lockMode = null, $lockVersion = null)
 * @method TodosList|null findOneBy(array $criteria, array $orderBy = null)
 * @method TodosList[]    findAll()
 * @method TodosList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TodosListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TodosList::class);
    }

    // /**
    //  * @return TodosList[] Returns an array of TodosList objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TodosList
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
