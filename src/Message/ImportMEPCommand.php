<?php

namespace App\Message;

use App\Comand\ImportMepsComand;
use App\Entity\MEP;
use Doctrine\ORM\EntityManagerInterface;
use SimpleXMLElement;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ImportMEPCommand extends Command
{

    protected static $defaultName = 'app:import-mep-messanger';
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $messageBus;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $messageBus)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->messageBus = $messageBus;
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

        $xml = new SimpleXMLElement($xmlContent);
        var_dump($xml);
        foreach ($xml->children() as $mep) {
            $url = $this->addMPEtoDB($mep);
            $this->messageBus->dispatch(new ImportMEPMessage($mep));
        }

        $io->success('MEPs import messages dispatched successfully.');

        return Command::SUCCESS;
    }

    private function addMPEtoDB($mep): string
    {
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

        // Fetch additional details from the MEP details page
        $isOk = ImportMepsComand::fetchAdditionalDetails($newMep);
        if($isOk === 'error'){
            $io->error('An error occurred while parsing the XML content.');
            return Command::FAILURE;
        }

        // Persist the MEP
        $this->entityManager->persist($newMep);
    }
}