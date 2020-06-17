<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\DataFixtures\ClassroomFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // ARTICLES ACTUALITES ADMIN (4)
        $article = new Article;
        $article->setTitle('Inscription rentrée scolaire 2021');
        $article->setContent('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Netus et malesuada fames ac turpis egestas integer. Venenatis a condimentum vitae sapien pellentesque habitant. Urna condimentum mattis pellentesque id nibh tortor id aliquet lectus. Id semper risus in hendrerit gravida rutrum. Pellentesque nec nam aliquam sem et tortor consequat id porta. Volutpat sed cras ornare arcu dui. Vivamus arcu felis bibendum ut. Nibh cras pulvinar mattis nunc sed blandit libero volutpat sed. Vestibulum sed arcu non odio euismod lacinia at. In iaculis nunc sed augue lacus. Praesent semper feugiat nibh sed pulvinar. Urna cursus eget nunc scelerisque viverra.');
        $article->setCategory(6);
        $manager->persist($article);

        $article = new Article;
        $article->setTitle('Fermeture exceptionnelle');
        $article->setContent('Blandit massa enim nec dui. Etiam sit amet nisl purus in mollis nunc sed id. Eu mi bibendum neque egestas congue. Et tortor at risus viverra adipiscing at in tellus integer. Erat nam at lectus urna duis convallis convallis tellus id. In ante metus dictum at tempor commodo ullamcorper a lacus. Amet luctus venenatis lectus magna fringilla urna porttitor. Ut placerat orci nulla pellentesque dignissim enim sit amet venenatis. Dignissim suspendisse in est ante in. Et malesuada fames ac turpis egestas integer. Elementum curabitur vitae nunc sed velit dignissim. Eu feugiat pretium nibh ipsum consequat nisl vel pretium. Nulla posuere sollicitudin aliquam ultrices sagittis. Sed ullamcorper morbi tincidunt ornare massa. Sollicitudin tempor id eu nisl nunc mi ipsum faucibus vitae. Augue neque gravida in fermentum et sollicitudin ac. Metus vulputate eu scelerisque felis. Sit amet porttitor eget dolor morbi non arcu risus.');
        $article->setCategory(0);
        $manager->persist($article);

        $article = new Article;
        $article->setTitle('Activités éducatives');
        $article->setContent('Eget nullam non nisi est sit amet. Consequat id porta nibh venenatis cras. Tincidunt tortor aliquam nulla facilisi cras. Penatibus et magnis dis parturient montes nascetur ridiculus. Pulvinar pellentesque habitant morbi tristique. Bibendum est ultricies integer quis auctor elit sed. Semper risus in hendrerit gravida. Quis eleifend quam adipiscing vitae proin sagittis nisl rhoncus. Orci phasellus egestas tellus rutrum tellus pellentesque eu tincidunt tortor. Gravida rutrum quisque non tellus orci ac. Cras adipiscing enim eu turpis egestas pretium aenean. Auctor augue mauris augue neque gravida in fermentum et. Ultrices sagittis orci a scelerisque purus. Vel quam elementum pulvinar etiam non quam lacus. Turpis egestas sed tempus urna.');
        $article->setCategory(3);
        $manager->persist($article);

        $article = new Article;
        $article->setTitle('Correspondance scolaire');
        $article->setContent('Fames ac turpis egestas sed tempus urna et pharetra. Porttitor lacus luctus accumsan tortor posuere ac ut. Eros in cursus turpis massa tincidunt dui ut. Est velit egestas dui id ornare. Velit laoreet id donec ultrices tincidunt. Suspendisse ultrices gravida dictum fusce ut placerat orci nulla pellentesque. Viverra mauris in aliquam sem. Non blandit massa enim nec dui nunc mattis enim. Facilisis magna etiam tempor orci eu. Massa ultricies mi quis hendrerit dolor magna eget est lorem. Erat imperdiet sed euismod nisi porta. Nibh mauris cursus mattis molestie a iaculis at erat pellentesque.');
        $article->setCategory(2);
        $manager->persist($article);


        // ARTICLES BLOG CLASSE1 ()
        $article = new Article;
        $article->setTitle('Bienvenue sur le blog');
        $article->setContent('Fames ac turpis egestas sed tempus urna et pharetra. Porttitor lacus luctus accumsan tortor posuere ac ut. Eros in cursus turpis massa tincidunt dui ut. Est velit egestas dui id ornare. Velit laoreet id donec ultrices tincidunt. Suspendisse ultrices gravida dictum fusce ut placerat orci nulla pellentesque. Viverra mauris in aliquam sem. Non blandit massa enim nec dui nunc mattis enim. Facilisis magna etiam tempor orci eu. Massa ultricies mi quis hendrerit dolor magna eget est lorem. Erat imperdiet sed euismod nisi porta. Nibh mauris cursus mattis molestie a iaculis at erat pellentesque.');
        $article->setCategory(2);
        $article->setClassroom($this->getReference('classroom'.(0)));
        $article->setImageName('5eea177013252003962701.png');
        $manager->persist($article);

        $article = new Article;
        $article->setTitle('Bienvenue sur le blog');
        $article->setContent('Fames ac turpis egestas sed tempus urna et pharetra. Porttitor lacus luctus accumsan tortor posuere ac ut. Eros in cursus turpis massa tincidunt dui ut. Est velit egestas dui id ornare. Velit laoreet id donec ultrices tincidunt. Suspendisse ultrices gravida dictum fusce ut placerat orci nulla pellentesque. Viverra mauris in aliquam sem. Non blandit massa enim nec dui nunc mattis enim. Facilisis magna etiam tempor orci eu. Massa ultricies mi quis hendrerit dolor magna eget est lorem. Erat imperdiet sed euismod nisi porta. Nibh mauris cursus mattis molestie a iaculis at erat pellentesque.');
        $article->setCategory(2);
        $article->setClassroom($this->getReference('classroom'.(0)));
        $article->setImageName('5eea177013252003962701.png');
        $manager->persist($article);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [ClassroomFixtures::class];
    }
}
