<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SortieRepository;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'inscription_inscription')]
    public function inscrire(int $id, SortieRepository $sortieRepository, EntityManagerInterface $entityManager): Response
    {
        $sortie = $sortieRepository->find($id);
        $sortie->setEtat('Ouvert');
        if ($sortie !== setEtat) {


            return new Response('Inscription réussie');
        } else {
            return new Response('Sortie non trouvée', 404);
        }
    }
}