<?php

namespace App\Form\Furniture;

use App\Entity\Furniture\Brand;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class BrandSelectorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('slug', EntityType::class, [
                'class' => Brand::class,
                'choice_label' => 'name',
                'choice_value' => 'slug',
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            //'data_class' => Brand::class,
        ]);
    }
}
