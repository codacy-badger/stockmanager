<?php

namespace App\Controller;

use App\Entity\Delivery;
use App\Entity\Operator;
use App\Form\DeliveryType;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/delivery")
 */
class DeliveryController extends Controller
{

    /**
     * @Route("/index", name="delivery_index")
     */
    public function index()
    {
        $deliveries = $this->getDoctrine()->getRepository(Delivery::class)->findWithIssues();

        return $this->render('admin/delivery/index.html.twig', [
            'deliveries' => $deliveries
        ]);
    }

    /**
     * Generate a pdf delivery file and persist object delivery
     *
     * @Route("/generate/{id}", name="delivery_generate", methods="GET|POST")
     * @param Request $request
     * @param Operator $operator
     * @throws \Exception
     */
    public function generate(Request $request, Operator $operator)
    {
        //get the token generated by twig form
        $submitedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delivery-generate', $submitedToken)) {


            //get user with only non notified issues from user repository
            /** @var Operator $myOperator */
            $myOperator = $this->getDoctrine()->getRepository(Operator::class)->getOneOperatorWithNonNotifedIssues($operator);


            //delivery object
            $delivery = new Delivery();
            $date = new \DateTime();

            $delivery->setDateCreation($date);



            foreach ($myOperator->getUsers() as $user) {
                foreach ($user->getIssues() as $issue) {

                    $delivery->addIssue($issue);
                    $issue->setDelivery($delivery);
                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($delivery);
            $em->flush();


            $this->addFlash('success', 'Le bon de livraison a bien été généré');

            return $this->redirectToRoute('notification_index');
        }

    }

    /**
     * @Route("/export/{id}", name="delivery_export", methods="GET|POST")
     * @param Request $request
     * @param Delivery $delivery
     * @throws \Exception
     */
    public function export(Request $request, Delivery $delivery)
    {
        //get the token generated by twig form
        $submitedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delivery-export', $submitedToken)) {


            // Configure Dompdf according to your needs
            $pdfOptions = new Options();
            $pdfOptions->set('defaultFont', 'Arial');
            $pdfOptions->set('isRemoteEnabled', TRUE);

            // Instantiate Dompdf with our options
            $dompdf = new Dompdf($pdfOptions);

            $context = stream_context_create([
                'ssl' => [
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                    'allow_self_signed' => TRUE
                ]
            ]);
            $dompdf->setHttpContext($context);


            //    $dompdf->setBasePath('/');

            // Retrieve the HTML generated in our twig file
            $html = $this->renderView('admin/delivery/pdfExport.html.twig', [
                'title' => "Bon de livraison",
                'delivery' => $delivery
            ]);

            // Load HTML to Dompdf
            $dompdf->loadHtml($html);

            // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
            $dompdf->setPaper('A4', 'portrait');

            // Render the HTML as PDF
            $dompdf->render();

            // Output the generated PDF to Browser (inline view)
            $dompdf->stream("mypdf.pdf", [
                "Attachment" => false
            ]);
            exit;
        }

    }

    /**
     * @Route("/edit/{id}", name="delivery_edit", methods="GET|POST")
     * @param Request $request
     * @param Delivery $delivery
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, Delivery $delivery)
    {
        $form = $this->createForm(DeliveryType::class, $delivery);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'Ajout bien effectué');
            return $this->redirectToRoute('delivery_edit', ['id' => $delivery->getId()]);

        }

        return $this->render('admin/delivery/edit.html.twig', [
            'form' => $form->createView(),
            'delivery' => $delivery
        ]);
    }


}
