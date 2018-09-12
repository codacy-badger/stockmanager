<?php

namespace App\Form;

use App\Entity\Operator;
use App\Entity\Transportation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperatorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom'])
            ->add('transportations', EntityType::class, [
                'class' => 'App\Entity\Transportation',
                'label' => 'Réseau',
                'choice_label' => 'tradeName',
                'multiple' => true,

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Operator::class,
        ]);
    }
}
