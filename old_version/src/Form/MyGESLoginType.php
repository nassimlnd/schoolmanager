<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class MyGESLoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'attr' => [
                    'autocomplete' => 'username',
                    'class' => 'px-3.5 py-2.5 border border-primary rounded-lg custom-shadow-xs w-full',
                    'placeholder' => 'Username',
                ],
                'label' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your username',
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    'autocomplete' => 'current-password',
                    'class' => 'px-3.5 py-2.5 border border-primary rounded-lg custom-shadow-xs w-full',
                    'placeholder' => 'Password',
                ],
                'label' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your password',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
