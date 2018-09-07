<?php

namespace App\Form;

use App\Entity\Issue;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('equipment', EntityType::class, [
                'class' => 'App\Entity\Equipment',
                'choice_label' => 'serial'
            ])
            ->add('symptoms', EntityType::class, [
                'class' => 'App\Entity\BreakdownSymptom',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true


            ])
            ->add('description', TextType::class, [
                'label' => 'Commentaires',


            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Issue::class,
        ]);
    }
}
