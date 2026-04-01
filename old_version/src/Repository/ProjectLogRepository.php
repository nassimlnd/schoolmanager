<?php

namespace App\Repository;

use App\Entity\ProjectLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectLog>
 */
class ProjectLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectLog::class);
    }

    public function getByProjectLogId($projectLogId)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.projectLogId = :projectLogId')
            ->setParameter('projectLogId', $projectLogId)
            ->getQuery()
            ->getOneOrNullResult();
    }

//    /**
//     * @return ProjectLog[] Returns an array of ProjectLog objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProjectLog
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
