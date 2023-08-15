<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ProfilType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil/modifier', name: 'profil_modifier', methods: 'POST')]
    public function modifierProfil(): Response
    {
        $profil = new Participant();
        $profilForm = $this->createForm(ProfilType::class, $profil);
        return $this->render('profil/modifier.html.twig', [
            'profilForm' => $profilForm->createView()
        ]);
    }
    #[Route('/profil/details/{id}', name: 'profil_details', requirements: ['id'=>'\d+'], methods: "GET")]
    public function detailsProfil(): Response
    {
        return $this->render('profil/details.html.twig');
    }
}
