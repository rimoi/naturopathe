<?php

namespace App\Form;

use App\Entity\Category;
use App\Enum\CategoryEnum;
use App\Enum\WorkEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('nameTranslate', TextType::class)
            ->add('type', ChoiceType::class, [
                'choices' => WorkEnum::toArray(),
                'attr' => [
                    'class' => 'js-select2',
                    'style' => "width: 100%",
                    'placeholder' => 'Choisir le métier...',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
