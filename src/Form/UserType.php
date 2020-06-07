<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('classroom')
            ->add('email')
            ->add('password', PasswordType::class)
            ->add('roles', ChoiceType::class, [
                'choices' => User::ROLES,
                'expanded' => false,
                'multiple' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'forms'
        ]);
    }

    // private function getChoices()
    // {
    //     $choices = User::CLASSROOM;
    //     $output = [];
    //     foreach($choices as $k => $v)
    //     {
    //         $output[$v] = $k;
    //     }
    //     return $output;
    // }
}
