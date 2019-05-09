<?php

namespace App\Form;

use App\Entity\Delivery;
use App\Entity\Equipment;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeliveryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('equipments', EntityType::class, [
                    'class' => Equipment::class,
                    'choice_label' => 'serial',
                    'multiple' => true,
                    'required' => false,
                ]
            )
        ->add('dateCreation', DateType::class, [
            'widget' =>'single_text'
        ])
        ->add('user', EntityType::class, [
            'class' => User::class,
            'choice_label' => 'lastname',
            'multiple' => false,
            'required' => false
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Delivery::class,
        ]);
    }
}
