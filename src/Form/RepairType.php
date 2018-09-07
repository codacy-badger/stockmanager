<?php

namespace App\Form;

use App\Entity\Repair;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepairType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate')
            ->add('endDate')
            ->add('description')
            ->add('technician', EntityType::class, [
                'class' => 'App\Entity\User',
                'choice_label' => 'firstname'
            ])
            ->add('image', EntityType::class, [
                'class' => 'App\Entity\Image',
                'choice_label' => 'alt'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Repair::class,
        ]);
    }
}
