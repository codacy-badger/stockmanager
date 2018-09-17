<?php

namespace App\Controller;

use App\Entity\Contract;
use App\Form\ContractType;
use App\Repository\ContractRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/contract")
 */
class ContractController extends AbstractController
{
    /**
     * @Route("/", name="contract_index", methods="GET")
     * @param ContractRepository $contractRepository
     * @return Response
     */
    public function index(ContractRepository $contractRepository): Response
    {
        return $this->render('admin/contract/index.html.twig', ['contracts' => $contractRepository->findAll()]);
    }

    /**
     * @Route("/new", name="contract_new", methods="GET|POST")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $contract = new Contract();
        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contract);
            $em->flush();

            return $this->redirectToRoute('contract_index');
        }

        return $this->render('admin/contract/new.html.twig', [
            'contract' => $contract,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contract_show", methods="GET")
     * @param Contract $contract
     * @return Response
     */
    public function show(Contract $contract): Response
    {
        return $this->render('admin/contract/show.html.twig', ['contract' => $contract]);
    }

    /**
     * @Route("/{id}/edit", name="contract_edit", methods="GET|POST")
     * @param Request $request
     * @param Contract $contract
     * @return Response
     */
    public function edit(Request $request, Contract $contract): Response
    {
        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contract_edit', ['id' => $contract->getId()]);
        }

        return $this->render('admin/contract/edit.html.twig', [
            'contract' => $contract,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contract_delete", methods="DELETE")
     * @param Request $request
     * @param Contract $contract
     * @return Response
     */
    public function delete(Request $request, Contract $contract): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contract->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contract);
            $em->flush();
        }

        return $this->redirectToRoute('contract_index');
    }
}
