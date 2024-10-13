<?php

namespace App\Form\Speedbuilding;

use App\Entity\Speedbuilding\Category;
use App\Entity\Furniture\Model;
use App\Repository\Furniture\ModelRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryNewType extends AbstractType
{
    public function __construct(private ModelRepository $repo) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('markdown')
            ->add('model', EntityType::class, [
                'choices' => $this->repo->findAllByBrand($options['brand']),
                'class' => Model::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
            'brand' => null,
        ]);
    }
}
