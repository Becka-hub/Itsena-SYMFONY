<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name',TextType::class, [
            'label' => 'Name',
            'attr' => ['class' => 'user_fname','placeholder'=>'name...',],
            'required'=>true
        ])
        ->add('firstName',TextType::class, [
            'label' => 'First name',
            'attr' => ['class' => 'user_fname','placeholder'=>'first name...',],
            'required'=>true
        ])
        ->add('adress',TextType::class, [
            'label' => 'Adress',
            'attr' => ['class' => 'user_fname','placeholder'=>'adress...',],
            'required'=>true
        ])
        ->add('email',EmailType::class, [
            'label' => 'Email',
            'attr' => ['class' => 'user_email','placeholder'=>'email...',],
            'required'=>true
        ])
        ->add('photo', FileType::class, [
            'label' => 'Photo',
            'required' => true,

        ])
            ->add('plainPassword', RepeatedType::class, [
                'type' =>PasswordType::class,
                'invalid_message'=>'The password fields must match',
                'options' => ['attr'=>['class'=>'password-field']],
                'required' => true,
                'first_options' => ['label' => 'Password','attr'=>['placeholder'=>'password...']],
                'second_options' => ['label' => 'Confirm Password','attr'=>['placeholder'=>'confirm password...']],
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('save', SubmitType::class,[
                'attr' => ['class' => 'btn w-100'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
