<?php

namespace App\Form;

use App\Entity\Issue;
use App\Entity\Transportation;
use App\Repository\TransportationRepository;
use Doctrine\ORM\EntityRepository;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;


class IssueType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('equipment', AutocompleteType::class, [
                'class' => 'App\Entity\Equipment',
                'label' => 'Serial number',
                'translation_domain' => 'messages',

            ])
            ->add('symptoms', EntityType::class, [
                'class' => 'App\Entity\Symptom',
                'label' => 'Problems found',
                'translation_domain' => 'messages',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.position', 'ASC');
                },
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'translation_domain' => 'messages'
            ]);


        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

            $form = $event->getForm();


            $formOptions = [
                'class' => Transportation::class,
                'choice_label' => 'name',
                'label' => 'Transit network',
                'translation_domain' => 'messages',
                'query_builder' => function (TransportationRepository $tr) use ($event) {
                    return $tr->createQueryBuilder('t')
                        ->andWhere(':op MEMBER OF t.operators')
                        ->setParameter('op', $event->getData()->getUser()->getOperator());
                }
            ];

            $form->add('transportation', EntityType::class, $formOptions);
        }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Issue::class,
        ]);
    }
}
