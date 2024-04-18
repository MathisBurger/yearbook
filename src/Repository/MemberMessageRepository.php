<?php

namespace App\Repository;

use App\Entity\MemberMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MemberMessage>
 *
 * @method MemberMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method MemberMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method MemberMessage[]    findAll()
 * @method MemberMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemberMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MemberMessage::class);
    }

    //    /**
    //     * @return MemberMessage[] Returns an array of MemberMessage objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?MemberMessage
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
