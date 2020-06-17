<?php

namespace App\Form;

use App\Entity\Message;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('recipient',EntityType::class, [
                'class' => User::class,
                'label' => 'Destinataire :', 
                'query_builder'=> function(UserRepository $repo) use ($options){
                    return $repo->findRecipientFor($options['user']);
                },
                // 'group_by' => 'roles'
                'group_by' => function (User $user){
                    if ($user->hasRole('ROLE_ADMIN'))
                    {
                        return 'Administrateur';
                    }
                    elseif ($user->hasRole('ROLE_TEACHER'))
                    {
                        return 'Instituteurs';
                    }else
                    {
                        return 'Élèves';
                    }
                }
            ])
            ->add('subject', TextType::class, ['label' => 'Objet :'])
            ->add('content', TextareaType::class, [
                'label' => 'Message :',
                'attr' => [
                    'class' => 'tinymce',
                    'rows' => 6
                ]
            ])
            // ->add('content', TextType::class, ['label' => 'Message :'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
$resolver->setRequired('user');
$resolver->setAllowedTypes('user', User::class);


        $resolver->setDefaults([
            'data_class' => Message::class
        ]);
    }
}
