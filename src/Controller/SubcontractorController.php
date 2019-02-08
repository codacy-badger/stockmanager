<?php

namespace App\Controller;

use App\Entity\SubcontractorRepair;
use App\Form\SubcontractorType;
use App\Services\DateDiffHour;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/subcontractor")
 * Class SubcontractorController
 * @package App\Controller
 */
class SubcontractorController extends AbstractController
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
     * @Route("/", name="subcontractor_index")
     */
    public function index()
    {

        $subcontractors = $this->em->getRepository(SubcontractorRepair::class)->findBy([
            'dateReturn' => null
        ]);


        return $this->render('admin/subcontractor/index.html.twig', [
            'subcontractors' => $subcontractors,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="subcontractor_edit", methods={"GET|POST"})
     * @param SubcontractorRepair $subcontractorRepair
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(SubcontractorRepair $subcontractorRepair, Request $request, DateDiffHour $dateDiffHour)
    {
        $form = $this->createForm(SubcontractorType::class, $subcontractorRepair);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            if (null == !$subcontractorRepair->getDateReturn()) {

                //total time to repair
                $hours = $dateDiffHour->getDiff($subcontractorRepair->getDateReturn(), $subcontractorRepair->getRepair()->getIssue()->getDateRequest());

                //put total hours into repair
                $subcontractorRepair->getRepair()->setUnavailability($hours);

                //set date real date end repair to repair entity
                $subcontractorRepair->getRepair()->setDateEnd($subcontractorRepair->getDateReturn());


            }


            $this->em->flush();
            $this->addFlash('success', "L' évènement chez le sous-traitant a bien été modifié");

            return $this->redirectToRoute('subcontractor_index');

        }

        return $this->render('admin/subcontractor/edit.html.twig', [
            'form' => $form->createView(),
            'subcontractor' => $subcontractorRepair
        ]);
    }


    /**
     * @Route("/count", name="subcontracotr_count")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function countInProcessed()
    {

        $count = $this->em->getRepository(SubcontractorRepair::class)->countNotEnded();

        return $this->render('admin/subcontractor/_count.html.twig', [
            'count' => $count,
        ]);
    }
}
