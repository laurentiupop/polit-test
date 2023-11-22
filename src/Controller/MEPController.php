<?php

namespace App\Controller;

use App\Entity\MEP;
use SimpleXMLElement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

class MEPController extends AbstractController
{
    /**
     * @Route("/api/persons", name="get_persons", methods={"GET"})
     */
    public function showPersonList(PersistenceManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $persons = $entityManager
            ->getRepository(MEP::class)
            ->findWithoutContact();

        $data = [];

        foreach ($persons as $person) {
            $name = explode(' ', $person["full_name"]);
            $data[] = [
                'id' => $person["mep_id"],
                'firstName' => $name[0],
                'lastName' => $name[1],
                'country' => $person["country"],
                'politicalGroup' => $person["political_group"],
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/api/person/{id}", name="get_persons_by_id", methods={"GET"})
     */
    public function showPersonListById(int $id,PersistenceManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $person = $entityManager
            ->getRepository(MEP::class)
            ->findOneBy(['mep_id' => $id]);

        if (!$person) {
            throw $this->createNotFoundException('Person not found');
        }
        $name = explode(' ', $person->getFullName());
        $contacts = [];
        $contactsFromDB = [
            'address' => $person->getAddress(),
            'phone' => $person->getPhone(),
            'email' => $person->getEmail(),
            'twitter' => $person->getTwitter(),
            'instagram' => $person->getInstagram(),
            'website' => $person->getWebsite(),
            'facebook' => $person->getFacebook(),
            'address2' => $person->getAddress2(),
            'phone2' => $person->getPhone2(),
        ];
        foreach ($contactsFromDB as $key => $value) {
            if($value != null)
                $contacts[] = [
                    'value' => $value,
                    'type' => $key
                ];
        }
        $data = [
            'id' => $person->getMepId(),
            'firstName' => $name[0],
            'lastName' => $name[1],
            'country' => $person->getCountry(),
            'politicalGroup' => $person->getPoliticalGroup(),
            'contacts' => $contacts
        ];

        return new JsonResponse($data);
    }
}