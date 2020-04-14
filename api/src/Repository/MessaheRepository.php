<?php

namespace App\Repository;

use App\Entity\Messahe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Messahe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Messahe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Messahe[]    findAll()
 * @method Messahe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessaheRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Messahe::class);
    }

    // /**
    //  * @return Messahe[] Returns an array of Messahe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Messahe
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
