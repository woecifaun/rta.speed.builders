<?php

namespace App\Form\Speedbuilding;

use App\Entity\Assembly\Assembly;
use App\Entity\Assembly\Category;
use App\Repository\Assembly\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
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

        // Data Mapper for Assembly::$time
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
            'data_class' => Assembly::class,
            'empty_data' => null,
            'model' => null,
        ]);
    }

//     /**
//      * @param Assembly|null $viewData
//      */
//     public function mapDataToForms($viewData, \Traversable $forms): void
//     {
//         // there is no data yet, so nothing to prepopulate
//         if (null === $viewData) {
//             return;
//         }

//         // invalid data type
//         if (!$viewData instanceof Assembly) {
//             throw new UnexpectedTypeException($viewData, Assembly::class);
//         }

//         /** @var FormInterface[] $forms */
//         $forms = iterator_to_array($forms);

//         // initialize form field values
//         $forms['hours']->setData($viewData->getRecordHours());
//         $forms['minutes']->setData($viewData->getRecordMinutes());
//         $forms['seconds']->setData($viewData->getRecordSeconds());
//         $forms['decimal']->setData($viewData->getRecordDecimal());
//     }

//     public function mapFormsToData(\Traversable $forms, &$viewData): void
//     {
//         /** @var FormInterface[] $forms */
//         $forms = iterator_to_array($forms);

//         // as data is passed by reference, overriding it will change it in
//         // the form object as well
//         // beware of type inconsistency, see caution below
// dump($viewData);die;
//         $time =
//             $forms['hours']->getData() * 3600 +
//             $forms['minutes']->getData() * 60 +
//             $forms['seconds']->getData() +
//             floatval("." . $forms['decimal']->getData());

//         $viewData->setTime($time);
//     }
}
