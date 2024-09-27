<?php

namespace App\Form\Assembly;

use App\Entity\Assembly\Category;
use App\Entity\Furniture\Model;
use App\Repository\Furniture\ModelRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function __construct(private ModelRepository $repo) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $brand = $options['data']->getModel()->getBrand() ?: $options['data']->tmpBrand; // >_< UGLY but working for now

        $builder
            ->add('name')
            ->add('markdown')
            ->add('model', EntityType::class, [
                'choices' => $this->repo->findAllByBrand($brand),
                'class' => Model::class,
                'choice_label' => 'name',
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
