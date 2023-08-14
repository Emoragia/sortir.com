<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModificationProfilController extends AbstractController
{
    #[Route('/edit/profil', name: 'app_modification_profil')]
    public function index(): Response
    {
        return $this->render('modification_profil/edit.html.twig', [
            'controller_name' => 'ModificationProfilController',
        ]);
    }
}
