<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('country',TextType::class, [
            'label' => 'Country',
            'attr' => ['class' => 'country','placeholder'=>'country...',],
            'required'=>true
        ])
        ->add('city',TextType::class, [
            'label' => 'City',
            'attr' => ['class' => 'city','placeholder'=>'city...',],
            'required'=>true
        ])
        ->add('adress',TextType::class, [
            'label' => 'Adress delivery',
            'attr' => ['class' => 'city','placeholder'=>'adress delivery...',],
            'required'=>true
        ])
        ->add('save', SubmitType::class,[
            'attr' => ['class' => 'btn w-100'],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}
