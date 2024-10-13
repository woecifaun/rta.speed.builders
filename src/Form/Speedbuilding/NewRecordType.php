<?php

namespace App\Form\Speedbuilding;

use App\Entity\Speedbuilding\Category;
use App\Entity\Speedbuilding\Record;
use App\Repository\Speedbuilding\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewRecordType extends AbstractType
{
    public function __construct(private CategoryRepository $repo) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url')
            ->add('originalEmailAddress', EmailType::class)
            ->add('date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('attempt')
            ->add('category', EntityType::class, [
                'choices' => $this->repo->findAllByModel($options['model']),
                'class' => Category::class,
                'choice_value' => 'id',
                'choice_label' => 'name',
            ])
        ;

        // Data Mapper for Category::$time
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
            'data_class' => Record::class,
            'empty_data' => null,
            'model' => null,
        ]);
    }
}
