<?php

namespace App\DataFixtures;

use App\Entity\Classroom;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ClassroomFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i <8; $i++)
        {
            $classroom = new Classroom();
            $classroom->setName('Classe '.($i+1));
            $classroom->setLevel(null);
            $manager->persist($classroom);
            $this->addReference('classroom'.$i, $classroom);
        }

        $manager->flush();
    }
}
