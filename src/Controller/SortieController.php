<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Repository\SortieRepository;
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
    

    #[Route('/sorties/details/{id}', name: 'sortie_details', requirements: ['id' => '\d+'], methods: ["GET"])]
    public function afficherSortie(): Response{

        return $this->render('main/accueil.html.twig');
    }

    #[Route('/sorties/modifier', name: 'sortie_modifier', methods: ['GET', 'POST'])]
    public function modifierSortie(){
        //TODO: renvoi vers une page modifier.html.twig
    }

    #[Route('/sorties/supprimer', name: 'sortie_supprimer', methods: ['GET, DELETE'])]
    public function supprimerSortie(){
        //TODO : renvoi vers la page d'accueil
    }
    #[Route('/sorties/annuler', name: 'sortie_annuler', methods: ['GET','POST'])]
    public function annulerSortie(): Response
    {
        return $this->render('sortie/annuler.html.twig');
    }
}
