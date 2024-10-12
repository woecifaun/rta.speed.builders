<?php

namespace App\Form\Speedbuilding;

use App\Service\Speedbuilding\Time;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('hours', IntegerType::class)
            ->add('minutes', IntegerType::class)
            ->add('seconds', IntegerType::class)
            ->add('milliseconds', IntegerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Time::class,
        ]);
    }
}
