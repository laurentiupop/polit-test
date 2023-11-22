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
     * @Route("/getmeps", name="get_meps")
     */
    public function showList(PersistenceManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $meps = $entityManager
            ->getRepository(MEP::class)
            ->findAll();

        // Serialize entities to JSON using the Symfony serializer
        return $meps;
    }
}