<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('username', TextType::class, [
                'label' => 'Login'
            ])
            ->add('password', PasswordType::class, ['label' => 'Mot de passe'])
            ->add('email', EmailType::class, [
                'label' => 'Email'
            ])
            ->add('authorization', EntityType::class, [
                'class' => 'App\Entity\Authorization',
                'choice_label' => 'name',
                'placeholder' => "Selectionnez l'autorisation",
                'label' => 'Authorisation'
            ])
            ->add('operator', EntityType::class, [
                'class' => 'App\Entity\Operator',
                'choice_label' => 'name',
                'placeholder' => 'Selectionnez la société',
                'label' => 'Société'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
