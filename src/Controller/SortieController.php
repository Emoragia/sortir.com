<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    #[Route('/sorties', name: 'sortie_list', methods: 'GET')]
    public function list(): Response
    {
        return $this->render('main/accueil.html.twig');
    }
    #[Route('/sorties/details/{id}', name: 'sortie_details', requirements: ['id'=>'\d+'], methods: "GET")]
    public function afficherSortie(){

    }

    #[Route('/sorties/modifier', name: 'sortie_modifier', methods: ['GET', 'POST', 'PUT'])]
    public function modifierSortie(){

    }

    #[Route('/sorties/supprimer', name: 'sortie_supprimer', methods: ['POST, DELETE'])]
    public function supprimerSortie(){

    }
    #[Route('/sorties/annuler', name: 'sortie_annuler', methods: ['POST', 'PATCH'])]
    public function annulerSortie(): Response
    {
        return $this->render('sortie/annuler.html.twig');
    }
}
