<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\isEmpty;

class ProfilController extends BaseController
{
    #[Route('/profil/modifier', name: 'profil_modifier', methods: ['GET','POST'])]
    public function modifierProfil(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $hasher
    ): Response
    {
        /** @var Participant $participant */
        $participant = $this->getUser();

        $profilForm = $this->createForm(ProfilType::class, $participant);
        $profilForm->handleRequest($request);
        if($profilForm->isSubmitted() && $profilForm->isValid()){

            $motPasseClair = $profilForm['motPasseClair']->getData();
//            dd($motPasseClair);
            //TODO : affichage erreur si mot de passe vide
            if(!is_null($motPasseClair) && !isEmpty(trim($motPasseClair)))
            {
                $participant->setMotPasse(
                    $hasher->hashPassword($participant, $motPasseClair)
                );
            }
            if(isEmpty($motPasseClair)){
                $this->addFlash('warning', 'Le mot de passe ne peut être une chaîne vide !');
                return $this->redirectToRoute('profil_modifier');
            }

           $entityManager->persist($participant);
           $entityManager->flush();

            $this->addFlash('success', 'Modifications enregistrées avec succès !');
            return $this->redirectToRoute('main_accueil');
        }
        return $this->render('profil/modifier.html.twig', [
            'profilForm'=>$profilForm->createView()
        ]);
    }
    #[Route('/profil/details/{id}', name: 'profil_details', requirements: ['id'=>'\d+'], methods: "GET")]
    public function detailsProfil(): Response
    {
        return $this->render('profil/details.html.twig');
    }
}
