<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventModifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('artiste', TextType::class, [
                'label' => 'Nom de l\'artiste à modifier',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('presentation', TextType::class, [
                'label' => 'Nom de la Présentation',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('date', TextType::class, [
                'label' => 'Date de la Présentation',
                'attr' => [
                    'placeholder' => 'JJ/MM/AAAA',
                    'class' => 'form-control'
                ]
            ])
            ->add('heure', TextType::class, [
                'label' => 'Heure de la Présentation',
                'attr' => [
                    'placeholder' => 'H:M',
                    'class' => 'form-control'
                ]
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse de la Présentation',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Selectionnez le nouveau Type de présentation...' => [
                        'concert' => 'Concert',
                        'spectacle' => 'Spectacle',
                        'humour' => 'Humour'
                    ]
                ]
            ])
            ->add('tarif', TextType::class,[
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
