<?php

namespace App\Repository;

use App\Entity\HelpGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HelpGroup>
 *
 * @method HelpGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method HelpGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method HelpGroup[]    findAll()
 * @method HelpGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HelpGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HelpGroup::class);
    }

//    /**
//     * @return HelpGroup[] Returns an array of HelpGroup objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?HelpGroup
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
