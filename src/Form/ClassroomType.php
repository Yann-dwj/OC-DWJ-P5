<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Classroom;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ClassroomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('year')
            ->add('level', ChoiceType::class, [
                'choices' => $this->getChoices()
            ])
            ->add('teacher',EntityType::class, [
                'class' => User::class,
                'query_builder'=> function(UserRepository $repo) use ($options){
                    return $repo->findRecipientFor($options['user']);
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('user');
        $resolver->setAllowedTypes('user', User::class);

        $resolver->setDefaults([
            'data_class' => Classroom::class,
            'translation_domain' => 'forms'
        ]);
    }

    private function getChoices()
    {
        $choices = Classroom::LEVEL;
        $output = [];
        foreach($choices as $k => $v)
        {
            $output[$v] = $k;
        }
        return $output;
    }
}
