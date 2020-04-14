<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('category', ChoiceType::class, [
                'choices' => $this->getChoices()
            ])
            // ->add('created_at')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'translation_domain' => 'forms'
        ]);
    }

    private function getChoices()
    {
        $choices = Article::CATEGORY;
        $output = [];
        foreach($choices as $k => $v)
        {
            $output[$v] = $k;
        }
        return $output;
    }
}
