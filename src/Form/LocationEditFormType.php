<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\Site;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('isOk', CheckboxType::class)

            ->add('equipment', AutocompleteType::class, [
                'class' => 'App\Entity\Equipment',
                'label' => 'Serial number',
                'translation_domain' => 'messages',
                'required' => false
            ])
            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'name'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
