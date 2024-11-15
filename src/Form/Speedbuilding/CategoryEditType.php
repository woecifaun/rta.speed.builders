<?php

/* Deprecated on 2024-11-11 */
namespace App\Form\Speedbuilding;

use App\Entity\Speedbuilding\Category;
use App\Entity\Furniture\Model;
use App\Repository\Furniture\ModelRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryEditType extends AbstractType
{
    public function __construct(private ModelRepository $repo) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Brand and model cannot be changed (for now ? is it relevant to change it ?)
        $builder
            ->add('name')
            ->add('markdown')
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
