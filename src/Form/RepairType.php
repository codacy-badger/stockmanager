<?php

namespace App\Form;

use App\Entity\Part;
use App\Entity\Repair;
use App\Entity\Symptom;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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


            ])
            ->add('parts', EntityType::class, [
                'class' => Part::class,
                'choice_label' => 'name',
                'multiple' => true,

            ])
            ->add('degradation', CheckboxType::class)
            ->add('noBreakdown', CheckboxType::class)
            ->add('timeToRepair', IntegerType::class)
            ->add('softVersion', TextType::class)
            ->add('statsDownload', CheckboxType::class)
            ->add('softUpload', CheckboxType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Repair::class,
            'translation_domain' => 'messages'
        ]);
    }
}
