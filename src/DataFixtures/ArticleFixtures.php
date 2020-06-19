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
        $article->setCategory(0);
        $article->setClassroom($this->getReference('classroom'.(0)));
        $manager->persist($article);

        $article = new Article;
        $article->setTitle('Liste de fournitures');
        $article->setContent('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi in libero orci. Mauris malesuada finibus eleifend. Etiam eros purus, hendrerit non diam non, accumsan vestibulum nulla. Ut convallis enim a metus ornare, id fringilla leo egestas. Sed accumsan sapien rhoncus urna fermentum, sit amet lobortis mauris consectetur. Curabitur id venenatis ipsum, a dictum lacus. Ut ultricies nunc ac venenatis tincidunt. Ut luctus, massa a gravida posuere, risus nisi vehicula odio, at pellentesque mi sem id nisl. Vestibulum fermentum dolor non felis placerat efficitur. Proin vitae dui vitae felis eleifend dapibus eu eget felis');
        $article->setCategory(3);
        $article->setClassroom($this->getReference('classroom'.(0)));
        $manager->persist($article);

        $article = new Article;
        $article->setTitle('Sortie scolaire');
        $article->setContent('Vivamus nec rutrum mi. Mauris lobortis libero volutpat, maximus urna non, fringilla metus. Nunc porttitor magna sed nisl pellentesque, eu finibus dui varius. Aliquam accumsan blandit massa vel lobortis. Suspendisse varius pharetra sapien, ut hendrerit justo. Praesent sit amet purus ac velit egestas tempus non varius ante. Nunc ultricies bibendum pharetra. Nulla ullamcorper, neque non consequat rhoncus, enim enim fermentum ex, sed imperdiet diam magna ut mi. Fusce placerat nisl eget leo iaculis, id bibendum massa cursus. Vestibulum interdum, ante et hendrerit vestibulum, ante leo dapibus tortor, id sagittis risus tellus vel massa. Aenean at gravida sapien.');
        $article->setCategory(2);
        $article->setClassroom($this->getReference('classroom'.(0)));
        $manager->persist($article);

        $article = new Article;
        $article->setTitle('Activités de motricité');
        $article->setContent('Nam nibh sem, mattis vel augue vel, bibendum commodo odio. Donec ornare sapien ut orci euismod malesuada. Fusce sit amet luctus sem. Morbi risus tortor, pharetra eget scelerisque non, varius in sem. Aliquam erat volutpat. Nulla aliquet velit eget orci bibendum varius. Vestibulum interdum leo at diam lobortis, nec aliquam nibh facilisis. Suspendisse molestie felis non metus posuere laoreet. Sed gravida pellentesque fringilla. Aenean posuere, nulla interdum fringilla tempor, sapien nibh auctor mauris, in vulputate dolor ante vitae nibh. Nulla facilisi. Sed lobortis odio eu dui rhoncus porttitor. Morbi rhoncus quis sapien congue consequat. Aliquam tristique mi a velit egestas consequat.');
        $article->setCategory(3);
        $article->setClassroom($this->getReference('classroom'.(0)));
        $manager->persist($article);

        $article = new Article;
        $article->setTitle('Aire de jeux');
        $article->setContent('Etiam at est vel nunc maximus pharetra a vel purus. Vestibulum eget justo lectus. In hac habitasse platea dictumst. Nullam mi lectus, ultricies at lacus et, pulvinar convallis felis. Aliquam erat volutpat. Nam sagittis pharetra purus a ornare. Curabitur sed nibh mi. Proin quis hendrerit neque, vel elementum libero. Sed et neque eget nulla consequat dapibus. Vivamus ac efficitur diam, ac porta elit. Nunc non quam at ligula lobortis pulvinar. Sed mollis faucibus diam, condimentum tincidunt leo ullamcorper ac. Nam fringilla lectus eget elit commodo convallis. Aenean vitae sem nec orci ultrices convallis quis ac velit. Praesent sit amet ante sodales nisl laoreet fringilla eu sit amet mi. Duis blandit egestas nibh nec vestibulum.');
        $article->setCategory(0);
        $article->setClassroom($this->getReference('classroom'.(0)));
        $manager->persist($article);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [ClassroomFixtures::class];
    }
}
