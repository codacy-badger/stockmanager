<?php

namespace App\Form;

use App\Entity\SubcontractorRepair;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubcontractorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateEntry', DateType::class, [
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('dateDispatch', DateType::class, [
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('dateReturn', DateType::class, [
                'widget' => 'single_text',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SubcontractorRepair::class,
        ]);
    }
}
