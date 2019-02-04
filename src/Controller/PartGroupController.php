<?php

namespace App\Controller;

use App\Entity\PartGroup;
use App\Form\PartGroupType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/part-group")
 */
class PartGroupController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="part-group_index" )
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $partGroups = $this->em->getRepository(PartGroup::class)->findAll();

        return $this->render('admin/partGroup/index.html.twig', [
            'partGroups' => $partGroups,
        ]);
    }

    /**
     * @Route("/new", name="part-group_new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $partGroup = new PartGroup();

        $form = $this->createForm(PartGroupType::class, $partGroup);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($partGroup);
            $this->em->flush();

            $this->addFlash('success', 'Le groupe de pièce a bien été ajouté');
            return $this->redirectToRoute('part-group_index');

        }

        return $this->render('admin/partGroup/new.html.twig', [
            'form' => $form->createView(),

        ]);

    }

    /**
     * @Route("/{id}/edit", name="part-group_edit", methods="GET|POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, PartGroup $partGroup)
    {
        $form = $this->createForm(PartGroupType::class, $partGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            $this->addFlash('success', 'Le groupe de pièce a bien été modifié');

            return $this->redirectToRoute('part-group_edit', ['id' => $partGroup->getId()]);
        }

        return $this->render('admin/partGroup/edit.html.twig', [
            'partGroup' => $partGroup,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="part-group_delete", methods="DELETE")
     * @param Request $request
     * @param PartGroup $partGroup
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Request $request, PartGroup $partGroup)
    {
        if ($this->isCsrfTokenValid('delete-partGroup', $request->request->get('_token'))) {

            $this->em->remove($partGroup);
            $this->em->flush();

            $this->addFlash('success', 'Le groupe de pièce a bien été supprimé');
        }

        return $this->redirectToRoute('part-group_index');
    }

}