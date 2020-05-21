<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Classroom;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        // ADMINISTRATEUR (1)
        $admin = new User;
        $admin
            ->setFirstname('admin')
            ->setLastname('super')
            ->setEmail('admin@mail.fr')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->encoder->encodePassword($admin, 'admin'));
        $manager->persist($admin);
        $this->addReference('user-admin', $admin);

        // INSTITUTEURS (8)
        for ($i = 0; $i < 8; $i++)
        {
            $teacher = new User;
            $classroom = $this->getReference('classroom'.$i);
            $teacher
                ->setRoles(['ROLE_TEACHER'])
                ->setFirstname($faker->unique()->firstName($gender = 'male'|'female'))
                ->setLastname($faker->unique()->lastName())
                ->setEmail($teacher->getLastName() . '@mail.fr')
                ->setPassword($this->encoder->encodePassword($teacher, '0000'))
                ->setClassroom($classroom);

            $classroom->setTeacher($teacher);

            $manager->persist($teacher);
            $this->addReference('user-teacher'.$i, $teacher);
        }

        // FAMILLE (120)
        for ($i = 0; $i < 24; $i++)
        {
            $family = new User;
            $family
                ->setRoles(['ROLE_USER'])
                ->setFirstname($faker->unique()->firstName($gender = 'male'|'female'))
                ->setLastname($faker->unique()->lastName())
                ->setEmail($family->getLastName() . '@mail.fr')
                ->setPassword($this->encoder->encodePassword($family, '0000'))
                ->setClassroom($this->getReference('classroom'.($i%8)));
            $manager->persist($family);
            $this->addReference('user-user'.$i, $family);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [ClassroomFixtures::class];
    }
}
