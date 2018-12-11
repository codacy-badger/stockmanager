<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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
                'label' => 'Login',
                'translation_domain' => 'messages'
            ])
            ->add('_password', PasswordType::class, [
                'label' => 'Password',
                'translation_domain' => 'messages'
            ])
            ->add('ok', SubmitType::class, [
                'label' => 'Submit',
                'translation_domain' => 'messages'
            ])
            ->getForm();

        return $this->render('admin/security/login.html.twig', [
            'form' => $form->createView(),
            'lastUsername' => $lastUsername,
            'error' => $error,
        ]);

    }

    /**
     * @Route("/member/changePassword", name="member_changePassword")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $currentUser = $this->getUser();
        $form = $this->createForm('App\Form\ChangePasswordType', $currentUser);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            if (!$passwordEncoder->isPasswordValid($currentUser, $currentUser->getOldPassword())) {

                $this->addFlash('danger', 'Le mot de passe saisi n\'est pas le bon');

                return $this->render('member/security/login.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            $encodeNewPassword = $passwordEncoder->encodePassword($currentUser, $currentUser->getNewPassword());

            $currentUser->setPassword($encodeNewPassword);

            $manager = $this->getDoctrine()->getManager();
            $manager->flush();


            $this->addFlash('success', 'Le nouveau mot de passe a bien été enregistré. Veuillez-vous reconnecter.');
            return $this->redirectToRoute('logout');

        }

        return $this->render('member/security/login.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

    }
}
