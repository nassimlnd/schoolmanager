<?php

namespace App\Repository;

use App\Entity\Grade;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Grade>
 */
class GradeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Grade::class);
    }

    public function getGradesByStudent($studentId)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.student = :studentId')
            ->setParameter('studentId', $studentId)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getGradesByStudentAndCourse($courseId, $studentId): ?Grade
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.course = :courseId')
            ->andWhere('g.student = :studentId')
            ->setParameter('courseId', $courseId)
            ->setParameter('studentId', $studentId)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    //    /**
    //     * @return Grade[] Returns an array of Grade objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Grade
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
