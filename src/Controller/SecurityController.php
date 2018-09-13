<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authUtils)
    {
        $error = $authUtils->getLastAuthenticationError();

        $lastUsername = $authUtils->getLastUsername();

        $form = $this->get('form.factory')
            ->createNamedBuilder(null)
            ->add('_username', null, [
                'label'=> 'Votre login'
            ])
            ->add('_password', PasswordType::class, [
                'label' => 'Votre mot de passe'
            ])
            ->add('ok', SubmitType::class, [
                'label' => 'Valider'
            ])
            ->getForm();

        return $this->render('admin/security/login.html.twig', [
            'form' => $form->createView(),
            'lastUsername' => $lastUsername,
            'error' => $error,
        ]);

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

    }
}
