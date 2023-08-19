<?php

namespace App\Controller;

use App\Data\SortieRechercheData;
use App\Entity\Campus;
use App\Entity\Participant;
use App\Form\SortieRechercheDataType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main_accueil', methods: ['GET', 'POST'])]
    public function listeSorties(SortieRepository $sortieRepository, Request $request): Response
    {
        //TODO: en fonction des options choisies, lancer les méthodes appropriées des Repository
        /** @var Participant $participant */
        $participant = $this->getUser();
        $data = new SortieRechercheData($participant);
        $rechercheForm = $this->createForm(SortieRechercheDataType::class, $data);
        $rechercheForm->handleRequest($request);
        $sorties = $sortieRepository->findSorties($data);
        return $this->render('main/accueil.html.twig', [
               'rechercheForm'=> $rechercheForm->createView(),
               'sorties'=>$sorties
       ]);
    }
}
