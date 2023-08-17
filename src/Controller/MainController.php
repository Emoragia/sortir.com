<?php

namespace App\Controller;

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
        $sorties = $sortieRepository->findAll();
        return $this->render('main/accueil.html.twig', [
            'sorties'=>$sorties
        ]);
    }
}
