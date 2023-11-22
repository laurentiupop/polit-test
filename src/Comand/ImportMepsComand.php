<?php

namespace App\Comand;

use App\Entity\MEP;
use Doctrine\ORM\EntityManagerInterface;
use SimpleXMLElement;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ImportMepsComand extends Command
{
    protected static $defaultName = 'app:import-meps';
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Import MEPs from the European Parliament XML source.')
            ->setHelp('This command imports MEPs from the specified XML source.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Fetch XML data from the source
        $httpClient = HttpClient::create();
        try {
            $response = $httpClient->request('GET', 'https://www.europarl.europa.eu/meps/en/full-list/xml/a');
        } catch (TransportExceptionInterface $e) {
        }
        $xmlContent = $response->getContent();

        try {
            $xml = new SimpleXMLElement($xmlContent);
            foreach ($xml->children() as $meps) {
                // Extract data from SimpleXMLElement
                $fullName = (string)$meps->fullName;
                $country = (string)$meps->country;
                $politicalGroup = (string)$meps->politicalGroup;
                $mepId = (string)$meps->id;
                $nationalPoliticalGroup = (string)$meps->nationalPoliticalGroup;

                // Create a new MEP instance
                $newMep = new MEP();
                $newMep->setFullName($fullName);
                $newMep->setCountry($country);
                $newMep->setPoliticalGroup($politicalGroup);
                $newMep->setMepId($mepId);
                $newMep->setNationalPoliticalGroup($nationalPoliticalGroup);

                // Persist the MEP
                $this->entityManager->persist($newMep);
            }

            $io->success('MEPs imported successfully.');
        } catch (\Exception $e) {
            $io->error('An error occurred while parsing the XML content.');
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}