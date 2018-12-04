<?php

namespace App\Controller;

use App\Entity\Delivery;
use App\Form\DeliveryType;
use App\Repository\DeliveryRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/delivery")
 */
class DeliveryController extends Controller
{
    /**
     * @Route("/", name="delivery_index", methods="GET")
     */
    public function index(DeliveryRepository $deliveryRepository): Response
    {
        return $this->render('admin/delivery/index.html.twig', ['deliveries' => $deliveryRepository->findAll()]);
    }

    /**
     * @Route("/new", name="delivery_new", methods="GET|POST")
     */
    public function new()
    {

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);


        $dompdf->setBasePath('/');

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('admin/delivery/pdf.html.twig', [
            'title' => "Bon de livraison"
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


    }

    /**
     * @Route("/{id}", name="delivery_show", methods="GET")
     */
    public function show(Delivery $delivery): Response
    {
        return $this->render('delivery/show.html.twig', ['delivery' => $delivery]);
    }

    /**
     * @Route("/{id}/edit", name="delivery_edit", methods="GET|POST")
     */
    public function edit(Request $request, Delivery $delivery): Response
    {
        $form = $this->createForm(DeliveryType::class, $delivery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('delivery_edit', ['id' => $delivery->getId()]);
        }

        return $this->render('delivery/edit.html.twig', [
            'delivery' => $delivery,
            'form' => $form->createView(),
        ]);
    }


}
