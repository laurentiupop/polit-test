<?php

namespace App\Comand;

use App\Entity\MEP;
use Doctrine\ORM\EntityManagerInterface;
use SimpleXMLElement;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
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

                // Fetch additional details from the MEP details page
                $isOk = $this->fetchAdditionalDetails($newMep);
                if($isOk === 'error'){
                    $io->error('An error occurred while parsing the XML content.');
                    return Command::FAILURE;
                }

                // Persist the MEP
                $this->entityManager->persist($newMep);
            }

            $io->success('MEPs imported successfully.');
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $io->error('An error occurred while parsing the XML content.');
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }

    public function fetchAdditionalDetails(MEP $mep)
    {
        // Fetch MEP details page content
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', "https://www.europarl.europa.eu/meps/en/{$mep->getMepId()}/{$this->slugify($mep->getFullName())}/home");
        try {
            $htmlContent = $response->getContent();

            // Parse HTML content with Symfony DomCrawler
            $crawler = new Crawler($htmlContent);

            // Extract contact information from the "Contacts" section
            $contacts = $crawler->filter('#contacts .erpl_contact-card');
            foreach ($contacts as $Key=>$contactNode) {
                $contact = new Crawler($contactNode);
                $contactPhone = $contact->filter('a')->text();
                $contactAddress = $contact->filter('.erpl_contact-card-list')->text();
                if($Key === 0){
                    $mep->setPhone($contactPhone);
                    $mep->setAddress($contactAddress);
                }else{
                    $mep->setPhone2($contactPhone);
                    $mep->setAddress2($contactAddress);
                }
            }

            // Extract from the "Presentation" section
            $socials = $crawler->filter('.erpl_social-share-horizontal a');
            foreach ($socials as $social) {
                $socialType = $social->getAttribute('data-original-title');
                $socialValue = $social->getAttribute('href');

                switch ($socialType) {
                    case 'E-mail':
                        $mep->setEmail($this->formatEmail($socialValue));
                        break;
                    case 'Facebook':
                        $mep->setFacebook($socialValue);
                        break;
                    case 'Twitter':
                        $mep->setTwitter($socialValue);
                        break;
                    case 'Instagram':
                        $mep->setInstagram($socialValue);
                        break;
                    case 'Website':
                        $mep->setWebsite($socialValue);
                        break;
                }
            }

        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
           var_dump($e->getMessage());
            return 'error';
        }
        // Add more logic to extract other electronic or social media references if needed
        return 'success';
    }

    // Helper function to create slugs
    private function slugify($text): string
    {
        $text = preg_replace('/[^a-z0-9]/i', '_', $text);
        $text = trim($text, '_');
        return strtolower($text);
    }

    private function formatEmail($email){
        return str_replace(['[dot]', '[at]'], ['.', '@'], $email);
    }
}