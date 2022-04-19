<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\CategoryRepository;

class FilterType extends AbstractType
{
    const PRICE = [500,1000,2000,3000,4000,5000];
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('category',EntityType::class,[
            'choice_label' => 'libelle',
            'choice_value' => 'id',
            'attr' => ['class' => 'libelle_category',],
            'class' => Category::class,
            'required'=>true,
            'placeholder'=>'category...',
            'query_builder' => function (CategoryRepository $er) {
                return $er->createQueryBuilder('c')
                    ->orderBy('c.libelle', 'ASC');
            },
             ])
        ->add('minimumPrice',ChoiceType::class,[
            'attr' => ['class' => 'minPrice','placeholder'=>'min...'],
            'placeholder'=>'min',
            'label'=> 'Price min',
            'required'=>false,
            'choices' => array_combine(self::PRICE,self::PRICE)
        ])
        ->add('maximumPrice',ChoiceType::class,[
            'attr' => ['class' => 'minPrice','placeholder'=>'max...'],
            'placeholder'=>'max',
            'label'=> 'Price max',
            'required'=>false,
            'choices' => array_combine(self::PRICE,self::PRICE)
        ])
        ->add('product',TextType::class, [
            'attr' => ['class' => 'product','placeholder'=>'product...',],
            'label'=>'Product',
            'required'=>false
        ])
        ->add('search',SubmitType::class, [
            'attr' => ['class' => 'btn_search btn w-100'],
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
