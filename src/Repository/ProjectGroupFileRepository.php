<?php

namespace App\Repository;

use App\Entity\ProjectGroupFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectGroupFile>
 */
class ProjectGroupFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectGroupFile::class);
    }

    public function getByFileId($fileId): ?ProjectGroupFile
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.fileId = :fileId')
            ->setParameter('fileId', $fileId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    //    /**
    //     * @return ProjectGroupFile[] Returns an array of ProjectGroupFile objects
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

    //    public function findOneBySomeField($value): ?ProjectGroupFile
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
