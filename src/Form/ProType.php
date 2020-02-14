<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\IsTrue;

class ProType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', RepeatedType::class, [
            'type' => EmailType::class,
            'invalid_message' => "L'email doit être identique.",
            'options' => ['attr' => ['class' => 'email-field']],
            'required' => true,
            'first_options'  => ['label' => 'Email'],
            'second_options' => ['label' => 'Confirmer l\'email'],
        ])
        
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Le mot de passe doit être identique.',
            'options' => ['attr' => ['class' => 'password-field']],
            'required' => true,
            'first_options'  => ['label' => 'Mot de passe'],
            'second_options' => ['label' => 'Confirmer le mot de passe'],
        ])
        ->add('nom', TextType::class, [
            'label' => 'Nom',
            'attr' => [
                'class' => 'form-control'
            ]
        ])
        ->add('prenom', TextType::class, [
            'label' => 'Prénom',
            'attr' => [
                'class' => 'form-control'
            ]
        ])
        ->add('adresse', TextType::class, [
            'label' => 'Adresse',
            'attr' => [
                'class' => 'form-control'
            ]
        ])
        ->add('codepostal', TextType::class, [
            'label' => 'Code Postal',
            'attr' => [
                'class' => 'form-control'
            ]
        ])
        ->add('ville', TextType::class, [
            'label' => 'Votre Ville',
            'attr' => [
                'class' => 'form-control'
            ]
        ])
        ->add('telephone', TextType::class, [
            'label' => 'Votre Numéro de Téléphone',
            'attr' => [
                'class' => 'form-control'
            ]
        ])
        ->add('siret', IntegerType::class, [
            'label' => 'Votre Numéro de Siret',
            'required' => true,
            'attr' => [
                'class' => 'form-control'
                
            ]
        ])
        ->add('agreeTerms', CheckboxType::class, [
            'mapped' => false,
            'constraints' => [
                new IsTrue([
                    'message' => 'Vous devez accepter les conditions générales.',
                ]),
            ],
        ])
        ->add('save', SubmitType::class, [
            'label' => 'Valider',
            'row_attr' => ['class' =>'text-right'],
            ]
        );
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
