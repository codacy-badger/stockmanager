<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\Site;
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

                //calcul le temps d'indisponibilité
                $hours = $dateDiffHour->getDiff($subcontractorRepair->getDateReturn(), $subcontractorRepair->getRepair()->getIssue()->getDateRequest());

                //ajoute le nombre total d'heures d' indisponibilité
                $subcontractorRepair->getRepair()->setUnavailability($hours);

                //ajoute la vraie date de réparation à l'entité
                $subcontractorRepair->getRepair()->setDateEnd($subcontractorRepair->getDateReturn());

                //réccupérer le site SITEOISE
                $homeSite = $this->em->getRepository(Site::class)->findOneBy(['id' => Site::SITEOISE]);

                $location = new Location();
                $location
                    ->setIsOk(true)
                    ->setDate($subcontractorRepair->getDateReturn())
                    ->setEquipment($subcontractorRepair->getRepair()->getIssue()->getEquipment())
                    ->setSite($homeSite);


            }

            //si la date d'envoi a été renseigné alors on enregistre la nouvelle localisation de l'équipement chez le sous traitant
            if (null == !$subcontractorRepair->getDateDispatch()) {

                //réccupérer le site VIX
                $vixSite = $this->em->getRepository(Site::class)->findOneBy(['id' => Site::VIX]);

                $location = new Location();
                $location
                    ->setIsOk(false)
                    ->setDate($subcontractorRepair->getDateDispatch())
                    ->setEquipment($subcontractorRepair->getRepair()->getIssue()->getEquipment())
                    ->setSite($vixSite);

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
