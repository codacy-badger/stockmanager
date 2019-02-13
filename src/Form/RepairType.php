<?php

namespace App\Form;

use App\Entity\Part;
use App\Entity\Repair;
use App\Entity\Symptom;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepairType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', TextareaType::class)
            ->add('symptoms', EntityType::class, [
                'class' => Symptom::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false


            ])
            ->add('parts', EntityType::class, [
                'class' => Part::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false

            ])
            ->add('degradation', CheckboxType::class, [
                'required' => false
            ])
            ->add('noBreakdown', CheckboxType::class, [
                'required' => false
            ])
            ->add('timeToRepair', IntegerType::class, [
                'required' => false
            ])
            ->add('softVersion', TextType::class, [
                'required' => false
            ])
            ->add('statsDownload', CheckboxType::class, [
                'required' => false
            ])
            ->add('softUpload', CheckboxType::class, [
                'required' => false
            ])
            ->add('isGoingToSubcontractor', CheckboxType::class, [
                'required' => false
            ])
            ->add('dateEnd', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => true,
                'model_timezone' => 'UTC',
                'view_timezone'  => 'Europe/Paris'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Repair::class,
            'translation_domain' => 'messages'
        ]);
    }
}
