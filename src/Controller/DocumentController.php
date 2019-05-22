<?php


namespace App\Controller;


use App\Entity\Document;

use App\Services\DocumentGetter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DocumentController extends AbstractController
{

    /**
     * @Route("admin/document/{id}/download", name="document_download")
     * @param Document $document
     * @return Response
     */
    public function downloadDocument(Document $document, DocumentGetter $awsS3Uploader)
    {


        $result = $awsS3Uploader->getDocumentFromPrivateBucket($document->getName());

        if ($result) {
            // Display the object in the browser
            header("Content-Type: {$result['ContentType']}");
            echo $result['Body'];

            return new Response();
        }

        return new Response('', 404);
    }

}