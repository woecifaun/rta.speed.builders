<?php

namespace App\Form\Assembly;

use App\Entity\Assembly;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url')
            ->add('originalEmailAddress')
            ->add('time', null, [
                'widget' => 'single_text',
            ])
            ->add('PostDate', null, [
                'widget' => 'single_text',
            ])
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('attempt')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Assembly::class,
        ]);
    }
}
