<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('oldPassword',PasswordType::class, [
                'label' => 'Ancien mot de passe',
                'always_empty' => false
            ])
            ->add('newPassword', PasswordType::class, [
                'label' => 'Nouveau mot de passe'
            ])
            ->add('passwordRetry', PasswordType::class, [
                'label' => 'Confirmation'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider'
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
