<?php

namespace App\Form;

use App\Entity\Part;
use App\Entity\Repair;
use App\Entity\Software;
use App\Entity\Symptom;
use App\Repository\SoftwareRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepairType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $brand = $options['brand'];

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
                'required' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC');
                },

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
            ->add('software', EntityType::class, [
                'class' => Software::class,
                'choice_label' => 'version',
                'label' => 'Software version',
                'query_builder' => function (SoftwareRepository $sr) use ($brand) {
                    return $sr->findByBrand($brand);
                }
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
                'view_timezone' => 'Europe/Paris'
            ]);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Repair::class,
            'translation_domain' => 'messages',
            'brand' => null,

        ]);
    }
}
