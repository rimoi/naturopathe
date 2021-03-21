<?php

namespace App\Form;

use App\Entity\Commune;
use App\Repository\CommuneRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    /** @var CommuneRepository */
    private $communeRepository;

    public function __construct(CommuneRepository $communeRepository)
    {
        $this->communeRepository = $communeRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $location = $options['request']->query->get($this->getBlockPrefix())['location'] ?? [];
        $keyword = $options['request']->query->get($this->getBlockPrefix())['keyword'] ?? '';

        $builder
            ->add('keyword', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'website.view.home.index.search.input.keyword',
                    'class' => 'w-75 input-search border-0'
                ],
                'data' => $keyword,
            ])
            ->add('location', EntityType::class, [
                'class' => Commune::class,
                'choices' => $this->communeRepository->findBy(['archived' => false]),
                'required' => false,
                'multiple' => true,
                'attr' => [
                    'placeholder' => 'website.view.home.index.search.input.location',
                ],
                'data' => $location ? $this->communeRepository->findBy(['id' => $location]) : []
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'request' => null,
        ]);
    }
}
