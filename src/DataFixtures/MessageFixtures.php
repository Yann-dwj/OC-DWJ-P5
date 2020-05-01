<?php

namespace App\DataFixtures;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $message = new Message();
        $message->setTransmitter($this->getReference('user-user'));
        $message->setRecipient($this->getReference('user-admin'));
        $message->setSubject('test');
        $message->setContent('Salut, je teste l\'envoi des messages');
        $manager->persist($message);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }

}