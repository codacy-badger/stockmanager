<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('image', EntityType::class, [
                'class' => 'App\Entity\Image',
                'choice_label' => 'alt'
            ])
            ->add('hoursPerDay', IntegerType::class)
            ->add('mtbf', IntegerType::class)
            ->add('isContractual', CheckboxType::class, [
                'required' => false
            ])
            ->add('isEmbeded', CheckboxType::class, [
                'required' => false
            ])
            ->add('contractualQuantity', IntegerType::class, [
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
