<?php


namespace App\Services;


use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;

class DocumentGetter
{

    private $s3Client;
    private $privateBucketName;


    public function __construct(string $privateBucketName, S3Client $s3Client)
    {

        $this->privateBucketName = $privateBucketName;

        $this->s3Client = $s3Client;
    }

    /**
     * @param $documentName
     * @return mixed
     */
    public function getDocumentFromPrivateBucket($documentName)
    {


        try {


            return $this->s3Client->getObject(
                [
                    'Bucket' => $this->privateBucketName,
                    'Key' => 'document/' . $documentName,
                ]
            );
        } catch (S3Exception $e) {
            // Handle your exception here
        }
    }
}