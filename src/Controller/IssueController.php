<?php

namespace App\Controller;

use App\Entity\Issue;
use App\Entity\User;
use App\Form\IssueType;
use App\Repository\IssueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;


class IssueController extends AbstractController
{
    /**
     * @Route("admin/issue", name="issue_index", methods="GET")
     */
    public function index(IssueRepository $issueRepository): Response
    {
        return $this->render('admin/issue/index.html.twig', ['issues' => $issueRepository->findAll()]);
    }


    /**
     * @Route("member/issue/new", name="issue_new", methods="GET|POST")
     */
    public function new(Request $request, Security $security): Response
    {
        $issue = new Issue();

        $form = $this->createForm(IssueType::class, $issue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //adds the current user into object issue
            $user = $security->getUser();
            $issue->setUser($user);

            //save into database
            $em = $this->getDoctrine()->getManager();
            $em->persist($issue);
            $em->flush();

            $this->addFlash('success', "La panne a bien été enregistrée");
            return $this->redirectToRoute('member_index');
        }

        return $this->render('member/issue/new.html.twig', [
            'issue' => $issue,
            'form' => $form->createView(),
            'isActiveNew' => true
        ]);
    }


    /**
     * @Route("admin/issue/{id}", name="issue_show", methods="GET")
     */
    public function show(Issue $issue): Response
    {
        return $this->render('admin/issue/show.html.twig', ['issue' => $issue]);
    }

    /**
     * @Route("admin/issue/{id}/edit", name="issue_edit", methods="GET|POST")
     */
    public function edit(Request $request, Issue $issue): Response
    {
        $form = $this->createForm(IssueType::class, $issue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('issue_edit', ['id' => $issue->getId()]);
        }

        return $this->render('admin/issue/edit.html.twig', [
            'issue' => $issue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/issue/{id}", name="issue_delete", methods="DELETE")
     */
    public function delete(Request $request, Issue $issue): Response
    {
        if ($this->isCsrfTokenValid('delete' . $issue->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($issue);
            $em->flush();
        }

        return $this->redirectToRoute('issue_index');
    }

    /**
     * @Route("admin/issue/validate-{id}", name="issue_validate", methods="VALIDATE")
     * @param Issue $issue
     */
    public function validate(Request $request, Issue $issue)
    {
        if ($this->isCsrfTokenValid('validate' . $issue->getId(), $request->request->get('_token'))) {

            $dateTime = new \DateTime();
            $technician = $this->getUser();

            $issue->setDateChecked($dateTime);
            $issue->setTechnician($technician);


            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', "Ticket validé!");
        }

        return $this->redirectToRoute('issue_index');

    }




}
