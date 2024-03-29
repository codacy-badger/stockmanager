<?php

namespace App\Form;

use App\Entity\Issue;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('equipment', AutocompleteType::class, [
                'class' => 'App\Entity\Equipment',
                'label' => 'Numéro de série du materiel en panne',


            ])
            ->add('equipmentReplace', AutocompleteType::class, [
                'class' => 'App\Entity\Equipment',
                'label' => 'Numéro de série du materiel remplaçant',
                'required' => false


            ])
            ->add('dateRequest', DateTimeType::class, [
                'model_timezone' => 'UTC',
                'view_timezone' => 'Europe/Paris'
            ])



        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Issue::class,
            'validation_groups' => false

        ]);
    }
}
