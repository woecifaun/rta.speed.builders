<?php

namespace App\Form\Assembly;

use App\Entity\Assembly\Assembly;
use App\Entity\Assembly\Category;
use App\Repository\Assembly\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewType extends AbstractType
{
    public function __construct(private CategoryRepository $repo) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url')
            ->add('originalEmailAddress', EmailType::class)
            ->add('time', null, [
                'widget' => 'single_text',
                'input_format' => 'H:i:s.u'
            ])
            ->add('date', null, [
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
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Assembly::class,
            'model' => null,
        ]);
    }
}
