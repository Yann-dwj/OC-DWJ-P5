<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Classroom;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Undocumented function
     *
     * @return QueryBuilder
     */
    public function findUserQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('u');
    }

    /**
     * Undocumented function
     *
     * @return Query
     */
    public function findAllUsersQuery(): Query
    {
        $query = $this->findUserQuery();
        $query->orderBy('u.id', 'DESC');
        return $query->getQuery();
    }

    /**
     * Undocumented function
     *
     * @param User $user
     * @return void
     */
    public function findRecipientFor(User $user){

        return $this->createQueryBuilder('u')
        ->where('u.classroom = :classroom OR u.roles LIKE :teacher OR u.roles LIKE :admin')
        ->setParameter('classroom', $user->getClassroom())
        ->setParameter('teacher', '%ROLE_TEACHER%')
        ->setParameter('admin', '%ROLE_ADMIN%')
        ->andWhere('u <> :user')
        ->setParameter('user', $user);
    }

    /**
     * Undocumented function
     *
     * @return []
     */
    public function findByRole(): array
    {
        $all = $this->findAll();
        $allUsers = $this->findBy(['id' => $all], ['classroom' => 'asc']);

        // $allUsers = $this->findAll();
        $users = [];
        $admins = [];
        $teachers = [];
        $students = [];

        foreach($allUsers as $user)
        {
            if($user->hasRole('ROLE_ADMIN'))
            {
                $admins[] = $user;
            }
            if($user->hasRole('ROLE_USER'))
            {
                $students[] = $user;
            }
            if($user->hasRole('ROLE_TEACHER'))
            {
                $teachers[] = $user;
            }
        }

        $users['students'] = $students;
        $users['teachers'] = $teachers;
        $users['admins'] = $admins;

        return $users;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function findByClassroom($classroom)
    {
        $allUsers = $this->findBy(['classroom' => $classroom]);
        
        $users = [];
        $students = [];

        foreach($allUsers as $user)
        {
            if($user->hasRole('ROLE_USER'))
            {
               $students[] = $user; 
            }
        }

        $users['student'] = $students;
    
        return $students;
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
