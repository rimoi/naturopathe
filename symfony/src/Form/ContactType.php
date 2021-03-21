<?php

namespace App\Form;

use App\Entity\Contact;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', TextType::class, ['required' => false])
            ->add('email', EmailType::class, ['label' => ' '])
            ->add('phone', TextType::class, ['required' => false])
            ->add('content', TextareaType::class, [
                'attr' =>  ['style' => 'height: 202px'],
            ])
            ->add('captcha', CaptchaType::class, [
                'width' => 250,
                'height' => 70,
                'length' => 4,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
