<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
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
            ->setFirstName('admin')
            ->setClassroom('PS')
            ->setEmail('admin@mail.fr')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->encoder->encodePassword($admin, 'admin'));
        $manager->persist($admin);
        $this->addReference('user-admin', $admin);

        // INSTITUTEURS (8)
        for ($i = 0; $i < 8; $i++)
        {
            $teacher = new User;
            $teacher
                ->setRoles(['ROLE_TEACHER'])
                ->setFirstname($faker->unique()->firstName($gender = 'male'|'female'))
                ->setEmail($teacher->getFirstName() . '@mail.fr')
                ->setPassword($this->encoder->encodePassword($teacher, '0000'))
                ->setClassroom(USER::CLASSROOM[$i]);
            $manager->persist($teacher);
            $this->addReference('user-teacher'.$i, $teacher);
        }

        // FAMILLE (120)
        for ($i = 0; $i < 120; $i++)
        {
            $family = new User;
            $family
                ->setRoles(['ROLE_USER'])
                ->setFirstname($faker->unique()->firstName($gender = 'male'|'female'))
                ->setEmail($family->getFirstName() . '@mail.fr')
                ->setPassword($this->encoder->encodePassword($family, '0000'))
                ->setClassroom($faker->randomElement($array = array('PS', 'MS', 'GS', 'CP', 'CE1', 'CM2', 'CM1', 'CM2'), $count = 1));
            $manager->persist($family);
            $this->addReference('user-user'.$i, $family);
        }

        $manager->flush();
    }
}
