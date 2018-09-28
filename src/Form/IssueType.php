<?php

namespace App\Form;

use App\Entity\Issue;
use App\Entity\Transportation;
use App\Repository\TransportationRepository;
use App\Repository\UserRepository;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class IssueType extends AbstractType
{
    private $security;


    public function __construct(Security $security, UserRepository $ur)
    {
        $this->security = $security;

    }

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


        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

            $form = $event->getForm();


            $formOptions = [
                'class' => Transportation::class,
                'choice_label' => 'name',
                'label' => 'Réseau de transport',
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
