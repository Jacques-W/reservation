<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventCreationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('artiste', TextType::class, [
                'label' => 'Votre Nom de Scene',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('presentation', TextType::class, [
                'label' => 'Le nom de votre presentation',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('date', DateTimeType::class, [
                'label' => 'La Date et l\'heure de votre représentation'
            ])
          
            ->add('adresse', TextType::class, [
                'label' => 'Votre Adresse',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Choisissez le Type de votre représentation' => [
                        'concert' => 'Concert',
                        'spectacle' => 'Spectacle',
                        'humour' => 'Humour'
                    ]
                ]
            ])
            ->add('tarif', TextType::class, [
                'label' => 'Le Prix de la représentation'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
