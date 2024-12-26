<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Exception\ServerException;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpClient\HttpClient;

class DocumentService 
{
    private LoggerInterface $logger;
    private Filesystem $fileSystem;
    private string $apiURL;
    private string $storageDir;

    public function __construct(string $apiURL, string $storageDir, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->fileSystem = new Filesystem;
        $this->apiURL = $apiURL;
        $this->storageDir = $storageDir;
    }

    public function geDocuments() : void
    {
        $client = HttpClient::create();

        try {
            $response = $client->request('GET', $this->apiURL);

            $statusCode = $response->getStatusCode();
            if ($statusCode !== 200) {
                $this->logger->error("Failed to get documents from API . Status code : " . $statusCode);
            }

            if (!$this->fileSystem->exists($this->storageDir)) {
                $this->fileSystem->mkdir($this->storageDir);
            }

            $documents = $response->toArray();

            foreach ($documents as $document) {
                $this->processDocument($document);
            }
        } catch (TransportException | ClientException | ServerException $e) {
            $this->logger->error('Error received while fetching the documents from the API : ' . $e->getMessage());
        } catch (\Exception $e) {
            $this->logger->error("Unexpected error while get the documents : " . $e->getMessage());
        }
    }

    private function processDocument(array $document) : void 
    {
        if (empty($document['certificate']) || empty($document['description']) || empty($document['doc_no']) || empty($document['description'])) {
            $this->logger->warning('Invalid data, missing required fileds : certificate, description, doc_no and certificate');
            return;
        }

        $fileName = sprintf('%s_%s.pdf', $document['description'], $document['doc_no']);
        $filePath = $this->storageDir . DIRECTORY_SEPARATOR . $fileName;

        try {
            $file = base64_decode($document['certificate']);

            if ($file === false) {
                $this->logger->error('Failed to decode the certificate file for the document ' . $document['doc_no']);
                return;
            }

            file_put_contents($filePath, $file);

            $this->logger->info('Document cerificated uploaded : ' . $document['doc_no']);
        } catch(\Exception $e) {
            $this->logger->error('Error while processing the document ' . $document['doc_no'] . ' : ' . $e->getMessage());
        }
    }
}