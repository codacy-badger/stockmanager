<?php

namespace App\Form;

use App\Entity\Issue;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('equipment', AutocompleteType::class, [
                'class' => 'App\Entity\Equipment',

            ])
            ->add('symptoms', EntityType::class, [
                'class' => 'App\Entity\Symptom',
                'label' => 'Problèmes constatés',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Issue::class,
        ]);
    }
}
