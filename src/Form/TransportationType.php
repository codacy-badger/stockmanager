<?php

namespace App\Form;

use App\Entity\Operator;
use App\Entity\Transportation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransportationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('tradeName')
            ->add('operators', EntityType::class, [
                'class' => Operator::class,
                'label' => 'Exploitant',
                'choice_label' => 'name',
                'multiple' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transportation::class,
        ]);
    }
}
