<?php

namespace App\Controller;

use App\Entity\Issue;
use App\Form\IssueType;
use App\Repository\IssueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class IssueController extends AbstractController
{
    /**
     * @Route("admin/issue", name="issue_index", methods="GET")
     * @param IssueRepository $issueRepository
     * @return Response
     */
    public function index(IssueRepository $issueRepository): Response
    {
        return $this->render('admin/issue/index.html.twig', ['issues' => $issueRepository->findAll()]);
    }


    /**
     * @Route("member/issue/new", name="issue_new", methods="GET|POST")
     * @param Request $request
     * @param Security $security
     * @return Response
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
     * @Route("admin/issue/show-new", name="issue_showNew")
     * @return Response
     */
    public function showNew()
    {
        $issues = $this->getDoctrine()->getRepository(Issue::class)->findBy([
            'dateChecked' => null
        ]);

        return $this->render('admin/issue/showNew.html.twig', [
            'issues' => $issues
        ]);
    }

    /**
     * @Route("admin/issue/show-checked", name="issue_showChecked")
     * @return Response
     */
    public function showChecked()
    {
        $issues = $this->getDoctrine()->getRepository(Issue::class)->getChecked();

        return $this->render('admin/issue/showChecked.html.twig', [
            'issues' => $issues
        ]);
    }

    /**
     * @Route("admin/issue/show-ready", name="issue_showReady")
     * @return Response
     */
    public function showReady()
    {
        $issues = $this->getDoctrine()->getRepository(Issue::class)->getReady();

        return $this->render('admin/issue/showReady.html.twig', [
            'issues' => $issues
        ]);
    }

    /**
     * @Route("admin/issue/show-end", name="issue_showEnd")
     * @return Response
     */
    public function showEnd()
    {
        $issues = $this->getDoctrine()->getRepository(Issue::class)->getEnd();

        return $this->render('admin/issue/showEnd.html.twig', [
            'issues' => $issues
        ]);
    }

    /**
     * @Route("admin/issue/{id}", name="issue_show", methods="GET")
     * @param Issue $issue
     * @return Response
     */
    public function show(Issue $issue): Response
    {
        return $this->render('admin/issue/show.html.twig', ['issue' => $issue]);
    }

    /**
     * @Route("admin/issue/{id}/edit", name="issue_edit", methods="GET|POST")
     * @param Request $request
     * @param Issue $issue
     * @return Response
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
     * @param Request $request
     * @param Issue $issue
     * @return Response
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
     * @param Request $request
     * @param Issue $issue
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
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

    /**
     * @Route("admin/issue/count-widget", name="issue_countWidget")
     * @return Response
     */
    public function countWidget()
    {
        $countNew = $this->getDoctrine()->getRepository(Issue::class)->countNew();
        $countPrepare = $this->getDoctrine()->getRepository(Issue::class)->countPrepare();
        $countReady = $this->getDoctrine()->getRepository(Issue::class)->countReady();
        $countEnd = $this->getDoctrine()->getRepository(Issue::class)->countReady();

        return $this->render('admin/issue/countWidget.html.twig', [
            'countNew' => $countNew,
            'countPrepare' => $countPrepare,
            'countReady' => $countReady,
            'countEnd' => $countEnd
        ]);
    }


}
