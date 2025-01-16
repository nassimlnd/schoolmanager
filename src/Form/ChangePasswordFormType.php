<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\PasswordStrength;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                    ],
                ],
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 8,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            'max' => 4096,
                        ]),
                        new PasswordStrength(),
                        new NotCompromisedPassword(),
                    ],
                    'attr' => [
                        'class' => 'px-3.5 py-2.5 border border-primary rounded-lg custom-shadow-xs w-full focus:ring-orange-500 focus:outline-none focus:border-none',
                        'placeholder' => 'New password',
                    ],
                    'label' => 'New password',
                    'label_attr' => [
                        'class' => 'text-secondary text-sm font-medium',
                    ],
                    'row_attr' => [
                        'class' => 'flex flex-col gap-1.5',
                    ],
                ],
                'second_options' => [
                    'row_attr' => [
                        'class' => 'mt-5 flex flex-col gap-1.5',
                    ],
                    'label_attr' => [
                        'class' => 'text-secondary text-sm font-medium',
                    ],
                    'label' => 'Confirm password',
                    'attr' => [
                        'class' => 'px-3.5 py-2.5 border border-primary rounded-lg custom-shadow-xs w-full',
                        'placeholder' => 'Confirm password',
                    ],
                ],
                'invalid_message' => 'The password fields must match.',
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
