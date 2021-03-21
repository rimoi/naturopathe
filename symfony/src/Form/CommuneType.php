<?php

namespace App\Form;

use App\Entity\Commune;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommuneType extends AbstractType
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('nameTranslator', TextType::class)
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
    }

    public function onPreSetData(FormEvent $event): void
    {
        $form = $event->getForm();
        $form->add('parent', EntityType::class, [
            'class' => Commune::class,
            'choices' => $this->entityManager->getRepository(Commune::class)->getCommunesParent(),
            'required' => false,
            'attr' => [
                'class' => 'js-select2',
                'style' => "width: 100%",
                'placeholder' => 'Choisir une Moghataa...',
            ],
            'empty_data' => $event->getData()->getParent() ?? null,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commune::class,
        ]);
    }
}
