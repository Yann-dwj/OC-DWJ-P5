<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User;
        $user->setFirstName('admin');
        $user->setClassroom('PS');
        $user->setEmail('admin@mail.fr');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->encoder->encodePassword($user, 'admin'));
        $manager->persist($user);

        $user = new User;
        $user->setFirstName('instituteur');
        $user->setClassroom('CE2');
        $user->setEmail('instituteur@mail.fr');
        $user->setRoles(['ROLE_TEACHER']);
        $user->setPassword($this->encoder->encodePassword($user, 'instituteur'));
        $manager->persist($user);

        $user = new User;
        $user->setFirstName('famille');
        $user->setClassroom('CP');
        $user->setEmail('famille@mail.fr');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->encoder->encodePassword($user, 'famille'));
        $manager->persist($user);

        $manager->flush();
    }
}
