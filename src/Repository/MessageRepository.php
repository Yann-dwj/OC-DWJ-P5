<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }


    // /**
    //  * @return Message[] Returns an array of Message objects
    //  */
    public function findByliaison($teacher, $student)
    {
        $qb = $this->createQueryBuilder('m');

        $studentToTeacher = $qb->expr()->andX();
        $studentToTeacher
            ->add('m.transmitter = :student')
            ->add('m.recipient = :teacher')
        ;

        $teacherToStudent = $qb->expr()->andX();
        $teacherToStudent
            ->add('m.transmitter = :teacher')
            ->add('m.recipient = :student')
        ;
        
        return $qb
            ->andWhere($teacherToStudent)
            ->orWhere($studentToTeacher)
            ->setParameter('teacher', $teacher)
            ->setParameter('student', $student)
            ->orderBy('m.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Message[] Returns an array of Message objects
    //  */
    // public function findByExampleField($value)
    // {
    //     return $this->createQueryBuilder('m')
    //         ->andWhere('m.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('m.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }

    // /**
    //  * @return Message[] Returns an array of Message objects
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
    public function findOneBySomeField($value): ?Message
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
