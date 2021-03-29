<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Commune;
use App\Repository\CommuneRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('content', CKEditorType::class, [
                'required' => false
            ])
            ->add('image', ImageType::class, [
                'required' => false
            ]);

//        $builder
//            ->add('titleTranslator', TextType::class)
//            ->add('firstPhone', TextType::class)
//            ->add('lastPhone', TextType::class, [
//                'required' => false
//            ])
//            ->add('categories', EntityType::class, [
//                'class' => Category::class,
//                'choices' => $this->em->getRepository(Category::class)->findBy(['archived' => false]),
//                'multiple' => true,
//                'required' => false,
//                'attr' => [
//                    'class' => 'js-select2',
//                    'style' => "width: 100%",
//                    'placeholder' => 'Choisir une catégorie...',
//                ]
//            ])
//            ->add('communes', EntityType::class, [
//                'class' => Commune::class,
//                'choices' => $this->em->getRepository(Commune::class)->findAll(),
//                'multiple' => true,
//                'required' => false,
//                'attr' => [
//                    'class' => 'js-select2',
//                    'style' => "width: 100%",
//                    'placeholder' => 'Choisir un tag ...',
//                ]
//            ])

        ;

//        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
    }

    public function onPreSetData(FormEvent $event): void
    {
        $form = $event->getForm();

        $form->add('communes', EntityType::class, [
            'class' => Commune::class,
            'query_builder' => static function (CommuneRepository $repository) {
                return $repository->createQueryBuilder('c')
                    ->innerJoin('c.parent', 'p')
                    ->addOrderBy('p.name', 'ASC')
                    ->addOrderBy('c.name', 'ASC');
            },
            'group_by' => static function (Commune $choice) {
                return $choice->getParent() ? $choice->getParent()->getName() : $choice->getName();
            },
            'mapped' => true,
            'label' => 'Moughataa',
            'multiple' => true,
            'required' => false,
            'attr' => [
                'class' => 'js-select2',
                'style' => "width: 100%",
                'placeholder' => 'Choisir une Mougataa ...',
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class
        ]);
    }
}
