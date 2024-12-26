<?php

namespace App\Command;

use App\Service\DocumentService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetDocumentsCommand extends Command
{
    private DocumentService $docService;

    public function __construct(DocumentService $docService)
    {
        parent::__construct();
        $this->docService = $docService;
    }

    protected function configure() : void
    {
        $this->setName('get:documents')
            ->setDescription('Get documents from the API and save certificate locally');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $this->docService->geDocuments();
        $output->writeln('Documents are retrieved successfully.');

        return Command::SUCCESS;
    }
}