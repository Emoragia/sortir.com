<?php

namespace App\Controller;

use App\Data\SortieRechercheData;
use App\Entity\Campus;
use App\Entity\Participant;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main_accueil', methods: ['GET', 'POST'])]
    public function listeSorties(SortieRepository $sortieRepository): Response
    {
        //TODO: en fonction des options choisies, lancer les méthodes appropriées des Repository
//        $sorties = $sortieRepository->findAll();
        /** @var Participant $participant */
        $participant = $this->getUser();
        $data = new SortieRechercheData($participant);
        $sorties = $sortieRepository->findSorties($data);
        return $this->render('main/accueil.html.twig', [
            'sorties'=>$sorties
        ]);
    }
}
