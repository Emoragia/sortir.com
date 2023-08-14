<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppercuProfilController extends AbstractController
{
    #[Route('/appercu/profil', name: 'app_appercu_profil')]
    public function index(): Response
    {
        return $this->render('appercu_profil/appercu.html.twig', [
            'controller_name' => 'AppercuProfilController',
        ]);
    }
}
