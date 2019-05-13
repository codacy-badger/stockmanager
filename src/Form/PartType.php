<?php

namespace App\Form;

use App\Entity\Part;
use App\Entity\PartGroup;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('reference', TextType::class,[
                'required' => false,
            ])
            ->add('repairTime', IntegerType::class,[
                'required' => false
            ])
            ->add('partGroup', EntityType::class, [
                'class' => PartGroup::class,
                'choice_label' => 'name'
            ])
            ->add('threshold', IntegerType::class, [
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Part::class,
        ]);
    }
}
