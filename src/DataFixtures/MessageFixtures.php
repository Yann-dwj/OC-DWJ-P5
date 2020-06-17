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
        $message->setTransmitter($this->getReference('user-teacher3'));
        $message->setRecipient($this->getReference('user-admin'));
        $message->setSubject('Question technique');
        $message->setContent('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');
        $message->setLiaison(false);
        $message->setOpenRecipient(true);
        $manager->persist($message);

        $message = new Message();
        $message->setTransmitter($this->getReference('user-teacher2'));
        $message->setRecipient($this->getReference('user-admin'));
        $message->setSubject('Actualités');
        $message->setContent('Aenean porttitor, leo eu lacinia dictum, risus augue auctor neque, nec lobortis magna mi nec elit. Curabitur mollis id risus non bibendum. Proin accumsan arcu sit amet sem finibus consectetur id a ligula. Cras ut pellentesque elit, at ultricies eros. Nunc iaculis maximus nisi, tempus dapibus magna euismod nec. In cursus ante ex, non rhoncus augue auctor vel. Aenean accumsan, est eu imperdiet hendrerit, lacus dui tempus metus, eget euismod nulla odio eget mauris. Donec porta facilisis quam. Vestibulum vulputate ornare erat vel placerat.');
        $message->setLiaison(false);
        $message->setOpenRecipient(true);
        $manager->persist($message);

        $message = new Message();
        $message->setTransmitter($this->getReference('user-teacher6'));
        $message->setRecipient($this->getReference('user-admin'));
        $message->setSubject('Informations');
        $message->setContent('Donec ex nisi, volutpat quis metus quis, porttitor faucibus elit. Suspendisse egestas neque libero, eleifend suscipit mauris imperdiet eget. Sed justo magna, feugiat et odio eu, eleifend feugiat felis. Etiam nec diam est. Proin ac metus lorem. Quisque ullamcorper ante non urna semper, nec pellentesque lorem aliquam. Duis varius risus ac efficitur sollicitudin. Cras sollicitudin metus tortor, accumsan suscipit tellus consequat quis.');
        $message->setLiaison(false);
        $message->setOpenRecipient(true);
        $manager->persist($message);

        $message = new Message();
        $message->setTransmitter($this->getReference('user-teacher5'));
        $message->setRecipient($this->getReference('user-admin'));
        $message->setSubject('Liste des élèves');
        $message->setContent('Fusce blandit diam sem. Donec in posuere massa. Nam vel justo non erat ultricies semper. Fusce ac neque nec magna gravida consectetur nec ut magna. Fusce pretium leo nulla, sed imperdiet dui luctus sed. Aenean blandit vehicula varius. Curabitur in urna eget arcu posuere eleifend. Nulla porta lorem et erat imperdiet, in mattis ipsum efficitur.');
        $message->setLiaison(false);
        $manager->persist($message);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }

}