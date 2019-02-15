<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Contract;
use App\Entity\Operator;
use App\Entity\Search;
use App\Entity\Site;
use App\Entity\Transportation;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('equipment', AutocompleteType::class, [
                'class' => 'App\Entity\Equipment',
                'label' => 'Serial number',
                'translation_domain' => 'messages',
                'required' => false
            ])

            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label'=> 'name',
                'required' => false

            ])

            ->add('brand', EntityType::class, [
                'class' => Brand::class,
                'choice_label'=> 'name',
                'required' => false

            ])


            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'name',
                'required' => false
            ])


            ->add('contract', EntityType::class, [
                'class' => Contract::class,
                'choice_label' => 'name',
                'required' => false
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
        ]);
    }
}
