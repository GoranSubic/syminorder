<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'required' => false,
//                'label' => false,
                'row_attr' => ['class' => 'col-sm-6'],
                'label_attr' => ['class' => 'reg-no-label'],
                'attr' => [
                    'placeholder' => 'form.registration.firstname.placeholder',
                ],
            ])
            ->add('lastName', TextType::class, [
                'required' => false,
                'row_attr' => ['class' => 'col-sm-6'],
                'label_attr' => ['class' => 'reg-no-label'],
                'attr' => [
                    'placeholder' => 'form.registration.lastName.placeholder',
                ],
            ])
            ->add('company', TextType::class, [
                'required' => false,
                'row_attr' => ['class' => 'col-sm-12'],
                'label_attr' => ['class' => 'reg-no-label'],
                'attr' => [
                    'placeholder' => 'form.registration.company.placeholder',
                ],
            ])
            ->add('address', TextType::class, [
                'required' => false,
                'row_attr' => ['class' => 'col-sm-12'],
                'label_attr' => ['class' => 'reg-no-label'],
                'attr' => [
                    'placeholder' => 'form.registration.address.placeholder',
                ],
            ])
            ->add('postalCode', NumberType::class, [
                'required' => false,
                'row_attr' => ['class' => 'col-sm-6'],
                'label_attr' => ['class' => 'reg-no-label'],
                'attr' => [
                    'placeholder' => 'form.registration.postalCode.placeholder',
                ],
            ])
            ->add('city', TextType::class, [
                'required' => false,
                'row_attr' => ['class' => 'col-sm-6'],
                'label_attr' => ['class' => 'reg-no-label'],
                'attr' => [
                    'placeholder' => 'form.registration.city.placeholder',
                ],
            ])
            ->add('country', TextType::class, [
                'required' => false,
                'row_attr' => ['class' => 'col-sm-12'],
                'label_attr' => ['class' => 'reg-no-label'],
                'attr' => [
                    'placeholder' => 'form.registration.country.placeholder',
                ],
            ])
            ->add('phone', TextType::class, [
                'required' => false,
                'row_attr' => ['class' => 'col-sm-12'],
                'label_attr' => ['class' => 'reg-no-label'],
                'attr' => [
                    'placeholder' => 'form.registration.phone.placeholder',
                ],
            ])
            ->add('email', EmailType::class, [
                'row_attr' => ['class' => 'col-sm-12'],
                'label_attr' => ['class' => 'reg-no-label'],
                'attr' => [
                    'placeholder' => 'form.registration.email.placeholder',
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Slažem se',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'row_attr' => ['class' => 'col-sm-12'],
                'label_attr' => ['class' => 'reg-no-label'],
                'attr' => [
                    'placeholder' => 'form.registration.password.placeholder',
                ],
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Password treba da bude barem {{ limit }} karaktera',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
