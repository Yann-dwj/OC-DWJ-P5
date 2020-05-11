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
        $message->setTransmitter($this->getReference('user-user2'));
        $message->setRecipient($this->getReference('user-admin'));
        $message->setSubject('test');
        $message->setContent('Nam feugiat turpis vel turpis aliquet, nec tristique libero venenatis. Proin sed massa a eros pharetra molestie. Suspendisse accumsan tempor nibh a pharetra. Maecenas semper velit quam. Praesent viverra neque odio, id tempor ipsum commodo et. Maecenas lobortis porta elit, id fringilla lectus consequat nec. Nullam laoreet suscipit tempor. Nam ultricies quis erat et tincidunt.');
        $manager->persist($message);

        $message = new Message();
        $message->setTransmitter($this->getReference('user-user2'));
        $message->setRecipient($this->getReference('user-admin'));
        $message->setSubject('test2');
        $message->setContent('Suspendisse id libero at risus tristique venenatis. Nullam quis leo malesuada, scelerisque nibh eget, tempor odio. Ut pellentesque eleifend velit in varius. Nulla facilisi. In erat mi, tincidunt ut magna eget, rhoncus vehicula tellus. Nam sodales sollicitudin turpis. Integer vulputate tempus tempor. Sed scelerisque ultricies tortor, a venenatis nisl rutrum vel. Nam suscipit dui sed nisl malesuada pretium. Fusce feugiat dictum erat in eleifend. Proin condimentum eu eros at condimentum. Donec tincidunt lorem ac dolor elementum, laoreet fringilla turpis imperdiet. Sed placerat nulla dui, accumsan bibendum neque fermentum sit amet. Nunc lacus odio, fringilla vel porta in, scelerisque ac nulla. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam tristique sagittis libero, sed iaculis mauris facilisis ut.');
        $manager->persist($message);

        $message = new Message();
        $message->setTransmitter($this->getReference('user-user2'));
        $message->setRecipient($this->getReference('user-admin'));
        $message->setSubject('test3');
        $message->setContent('Nam a nulla eleifend, porttitor eros ac, pellentesque est. Nulla eu consequat velit. Donec eleifend turpis ac facilisis euismod. Donec a nibh eu justo interdum lobortis ut quis magna. Fusce tempor nisl ut tristique euismod. Ut laoreet maximus dignissim. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nunc consectetur metus vel vulputate finibus. Morbi iaculis urna quis nisl ullamcorper fringilla. Sed consectetur pellentesque arcu non rutrum. Nunc viverra convallis ex vel mattis. In id felis eget elit blandit sollicitudin sed ut nunc.');
        $manager->persist($message);

        $message = new Message();
        $message->setTransmitter($this->getReference('user-user2'));
        $message->setRecipient($this->getReference('user-admin'));
        $message->setSubject('test4');
        $message->setContent('Vestibulum quis facilisis arcu. Aliquam blandit risus vitae nisi iaculis, ac tristique leo lobortis. Nulla tincidunt nunc eu ultricies hendrerit. Donec non tincidunt lectus. Sed pellentesque efficitur ligula quis imperdiet. Aenean quam justo, varius at dui id, varius varius ante. Duis in orci vestibulum, ullamcorper libero id, eleifend leo. Donec id vehicula risus. Morbi condimentum rutrum pellentesque. Sed molestie auctor orci, at tempor ante.');
        $manager->persist($message);

        $message = new Message();
        $message->setTransmitter($this->getReference('user-user2'));
        $message->setRecipient($this->getReference('user-admin'));
        $message->setSubject('test5');
        $message->setContent('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in dolor a urna porta gravida. Aliquam eget convallis massa, sit amet scelerisque metus. Morbi sit amet felis at arcu maximus tristique. Etiam felis massa, malesuada ac molestie nec, semper a orci. Nam id libero sed ipsum rutrum elementum id at velit. Nam porta nulla eget nisl gravida, quis volutpat massa sagittis. Proin urna nisi, rhoncus non dapibus id, sodales non lorem.');
        $manager->persist($message);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }

}